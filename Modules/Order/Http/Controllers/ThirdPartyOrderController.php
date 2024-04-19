<?php

namespace Modules\Order\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Order\Services\ThirdPartyOrderService;
use Modules\Order\Transformers\OrderResource;

class ThirdPartyOrderController extends Controller
{
    use HttpResponse;

    private ThirdPartyOrderService $thirdPartyOrderService;

    public function __construct(ThirdPartyOrderService $thirdPartyOrderService)
    {
        $this->thirdPartyOrderService = $thirdPartyOrderService;
    }

    public function index()
    {
        return $this->paginatedResponse(
            $this->thirdPartyOrderService->index(),
            OrderResource::class,
        );
    }

    public function show($order)
    {
        return $this->resourceResponse(
            OrderResource::make($this->thirdPartyOrderService->show($order))
        );
    }
}
