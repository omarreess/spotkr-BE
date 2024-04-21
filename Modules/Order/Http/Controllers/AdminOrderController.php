<?php

namespace Modules\Order\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\LeaderBoard\Services\AchievementService;
use Modules\Order\Services\AdminOrderService;
use Modules\Order\Transformers\OrderResource;

class AdminOrderController extends Controller
{
    use HttpResponse;

    private AdminOrderService $adminOrderService;

    public function __construct(AdminOrderService $adminOrderService)
    {
        $this->adminOrderService = $adminOrderService;
    }

    public function index()
    {
        return $this->paginatedResponse(
            $this->adminOrderService->index(),
            OrderResource::class,
        );
    }

    public function show($order)
    {
        return $this->resourceResponse(
            OrderResource::make($this->adminOrderService->show($order))
        );
    }

    public function finish($order, AchievementService $achievementService)
    {
        $this->adminOrderService->finish($order, $achievementService);

        return $this->okResponse(message: translate_success_message('order', 'finished'));
    }
}
