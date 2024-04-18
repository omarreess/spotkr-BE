<?php

namespace Modules\Order\Services;


use App\Exceptions\ValidationErrorsException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Helpers\ActivityHelper;
use Modules\Coupon\Services\CouponService;
use Modules\FcmNotification\Notifications\FcmNotification;
use Modules\Order\Entities\Order;
use Modules\Payment\Entities\Card;
use Modules\Payment\Services\StripeCardService;
use Modules\Payment\Services\StripeChargeService;
use Modules\Payment\Services\StripeCustomerService;

class ClientOrderService extends BaseOrderService
{
    private CouponService $couponService;

    public function __construct(Order $orderModel, CouponService $couponService)
    {
        parent::__construct($orderModel);

        $this->couponService = $couponService;
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
                'activity' => fn($builder) => $builder->select([
                    'id',
                    'name',
                    'description',
                    'type',
                ])
                    ->with('mainImage')
            ])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function store(array $data)
    {
        //TODO get activity

        $activity = Activity::query()->whereValidForClient()->findOrFail($data['activity_id']);

        //TODO

        if(isset($data['coupon']))
        {
            $coupon = $this->couponService->getValidCouponByName($data['coupon']);
        }

        $data['cost'] = $this->calculateOrderCost(
            $activity,
            isset($coupon) ? $coupon->discount : null,
            $data['sessions_count'] ?? null,
        );

        $order = DB::transaction(function() use ($data){

            $order = $this->orderModel::create($data);

            $serverCreditCard = Card::query()
                ->whereId($data['credit_card_id'])
                ->where('user_id', auth()->id())
                ->first();

            if(! $serverCreditCard)
            {
                throw new ValidationErrorsException([
                    'credit_card_id' => translate_error_message('credit_card', 'not_exists')
                ]);
            }
            $stripeCustomerService = new StripeCustomerService();
            $stripeCustomer = $stripeCustomerService->retrieveCustomer();

            $creditCard = (new StripeCardService())->retrieve($stripeCustomer->id, $serverCreditCard->stripe_card_id);

            (new StripeChargeService())->store(
                $creditCard->id,
                $stripeCustomer->id,
                $data['cost'],
            );

            return $order;
        });

        //TODO notify third party about that order
        $this->notifyCreatedOrder($order->id, $activity);
    }

    private function calculateOrderCost(Activity $activity, float|null $couponPercentage, int|null $sessionsCount)
    {
        $orderCost = ActivityHelper::calculatePrice($activity, $sessionsCount);

        if($couponPercentage)
        {
            $orderCost = ($orderCost * $couponPercentage) / 100;
        }

        return $orderCost;
    }

    private function notifyCreatedOrder($orderId, Activity $activity)
    {
        $activity->thirdParty->notify(new FcmNotification(
            'order_created_title',
            'order_created_body',
            additionalData: [
                'model_id' => $orderId,
            ],
            shouldTranslate: [
                'title' => true,
                'body' => true,
            ],
            translatedAttributes: [
                'order_created_body' => [
                    'id' => $orderId,
                ]
            ]
        ));
    }

    public function cancel($order)
    {
        $order = $this->orderModel::query()
            ->wherePending()
            ->where('user_id', auth()->id())
            ->findOrFail($order);
    }
}
