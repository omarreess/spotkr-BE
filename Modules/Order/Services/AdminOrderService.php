<?php

namespace Modules\Order\Services;

use Modules\FcmNotification\Enums\NotificationTypeEnum;
use Modules\LeaderBoard\Services\AchievementService;
use Modules\Order\Enums\OrderStatusEnum;

class AdminOrderService extends BaseOrderService
{
    public function index()
    {
        return $this
            ->baseIndex()
            ->with([
                'activity' => fn ($builder) => $builder->select([
                    'id',
                    'name',
                    'description',
                    'type',
                ])
                    ->with('mainImage'),
            ])
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
            ->findOrFail($id);
    }

    public function finish($order, AchievementService $achievementService)
    {
        $order = $this->orderModel::query()
            ->wherePaymentDone()
            ->findOrFail($order);

        $order
            ->forceFill(['status' => OrderStatusEnum::COMPLETED])
            ->save();

        $achievementService->updateTotalAchievements($order->activity);

        ClientOrderService::notifyForOrder(
            $order->user,
            $order->id,
            'order_finished_title',
            'order_finished_body',
            NotificationTypeEnum::ORDER_COMPLETED,
        );
    }
}
