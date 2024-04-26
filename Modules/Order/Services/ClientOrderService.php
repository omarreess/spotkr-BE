<?php

namespace Modules\Order\Services;

use App\Exceptions\ValidationErrorsException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Helpers\ActivityHelper;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Coupon\Services\CouponService;
use Modules\FcmNotification\Enums\NotificationTypeEnum;
use Modules\FcmNotification\Notifications\FcmNotification;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Payment\Services\StripeCardService;
use Modules\Payment\Services\StripeChargeService;
use Modules\Payment\Services\StripeCustomerService;

class ClientOrderService extends BaseOrderService
{
    private Activity $activityModel;

    private CouponService $couponService;

    private StripeCardService $stripeCardService;

    private StripeChargeService $stripeChargeService;

    private StripeCustomerService $stripeCustomerService;

    public function __construct(
        Activity $activityModel,
        Order $orderModel,
        CouponService $couponService,
        StripeCardService $stripeCardService,
        StripeChargeService $stripeChargeService,
        StripeCustomerService $stripeCustomerService
    ) {
        parent::__construct($orderModel);
        $this->activityModel = $activityModel;
        $this->couponService = $couponService;
        $this->stripeCardService = $stripeCardService;
        $this->stripeChargeService = $stripeChargeService;
        $this->stripeCustomerService = $stripeCustomerService;
    }

    public function index()
    {
        return $this
            ->baseIndex()
            ->where('user_id', auth()->id())
            ->paginatedCollection();
    }

    public function show($id)
    {
        return $this->orderModel::query()
            ->with([
                'activity' => fn ($builder) => $builder->select([
                    'id',
                    'name',
                    'description',
                    'type',
                ])
                    ->with('mainImage'),
            ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function store(array $data)
    {
        $activity = $this->activityModel::whereValidForClient()->findOrFail($data['activity_id']);

        if (isset($data['coupon'])) {
            $coupon = $this->couponService->getValidCouponByName($data['coupon']);
        }

        $data['cost'] = $this->calculateOrderCost(
            $activity,
            isset($coupon) ? $coupon->discount : null,
            $data['sessions_count'] ?? null,
        );

        $order = $this->orderModel::create($data);

        $this->notifyCreatedOrder($order->id, $activity);
    }

    public function payOrder(...$args): void
    {
        DB::transaction(function () use ($args) {
            [$creditCardId, $order] = $args;

            $order = $this->getPendingOrder($order);

            $serverCreditCard = $this->stripeCardService->creditCardExists($creditCardId);

            $order
                ->forceFill(['status' => OrderStatusEnum::PAYMENT_DONE])
                ->save();

            $stripeCustomer = $this->stripeCustomerService->retrieveCustomer();
            $creditCard = $this->stripeCardService->retrieve($stripeCustomer->id, $serverCreditCard->stripe_card_id);
            $this->stripeChargeService->store(
                $creditCard->id,
                $stripeCustomer->id,
                $order->cost,
            );
        });
    }

    private function calculateOrderCost(Activity $activity, ?float $couponPercentage, ?int $sessionsCount): float|int
    {
        $orderCost = ActivityHelper::calculatePrice($activity, $sessionsCount);

        if ($couponPercentage) {
            $orderCost = ($orderCost * $couponPercentage) / 100;
        }

        return $orderCost;
    }

    public function cancel($order)
    {
        $order = $this->orderModel::query()
            ->whereIn('status', [OrderStatusEnum::PENDING, OrderStatusEnum::PAYMENT_DONE])
            ->findOrFail($order);

        $oldStatus = $order->status;
        $order->forceFill(['status' => OrderStatusEnum::CANCELED])->save();

        if ($oldStatus == OrderStatusEnum::PAYMENT_DONE) {
            $this->notifyCanceledOrder($order->id);
        }
    }

    private function getPendingOrder($orderId)
    {
        return $this->orderModel::query()
            ->wherePending()
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);
    }

    private function notifyCreatedOrder($orderId, Activity $activity): void
    {
        self::notifyForOrder($activity->thirdParty, $orderId);
    }

    private function notifyCanceledOrder($orderId): void
    {
        self::notifyForOrder(
            User::where('type', UserTypeEnum::ADMIN)->first(),
            $orderId,
            'order_canceled_title',
            'order_canceled_body',
            NotificationTypeEnum::ORDER_CANCELED,
        );
    }

    public static function notifyForOrder($user, $orderId, $title = 'order_created_title', $body = 'order_created_body', $type = NotificationTypeEnum::ORDER_CREATED): void
    {
        $user->notify(new FcmNotification(
            $title,
            $body,
            additionalData: [
                'model_id' => $orderId,
                'type' => $type,
            ],
            shouldTranslate: [
                'title' => true,
                'body' => true,
            ],
            translatedAttributes: [
                $body => [
                    'id' => $orderId,
                ],
            ]
        ));
    }

    public function review(...$args)
    {
        DB::transaction(function () use ($args) {
            [$data, $order, $reviewService] = $args;

            $order = $this->orderModel::query()
                ->whereCompleted()
                ->where('user_id', auth()->id())
                ->where('rated', false)
                ->findOrFail($order);

            $reviewService->handle($data, $order->activity);
            $order->forceFill(['rated' => true])->save();
        });
    }
}
