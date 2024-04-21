<?php

namespace Modules\LeaderBoard\Services;

use App\Models\User;
use Modules\Activity\Enums\ActivityTypeEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Order\Enums\OrderStatusEnum;

class BaseLeaderBoardService
{
    protected User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    protected function baseIndex(array $filters)
    {
        $period = $filters['period'] ?? null;

        return $this->userModel::query()
            ->select(['id', 'name', 'username', 'last_winning_time'])
            ->with('avatar')
            ->where('type', UserTypeEnum::CLIENT)
            ->leaderboardFilters($filters)
            ->withCount(['orders' => function($query) use ($period){
                $query
                    ->where('status', OrderStatusEnum::COMPLETED)
                    ->whereHas('activity', function($query){
                        $query
                            ->where('type', ActivityTypeEnum::SPORT);
                    })
                    ->when(
                        $period,
                        fn($builder) => $builder
                            ->whereBetween(
                                'created_at',
                                $this->getPeriodDateRange($period)
                            )
                    );
            }]);
    }

    private function getPeriodDateRange(string $period)
    {
        return match ($period) {
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'day' => [now()->startOfDay(), now()->endOfDay()],
            default => [now()->startOfYear(), now()->endOfYear()],
        };
    }
}
