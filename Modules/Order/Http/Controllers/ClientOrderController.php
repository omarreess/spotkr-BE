<?php

namespace Modules\Order\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Services\ClientOrderService;
use Modules\Order\Transformers\OrderResource;

class ClientOrderController extends Controller
{
    use HttpResponse;

    private ClientOrderService $clientOrderService;

    public function __construct(ClientOrderService $clientOrderService)
    {
        $this->clientOrderService = $clientOrderService;
    }

    public function index()
    {
        $orders = $this->clientOrderService->index();

        return $this->paginatedResponse($orders, OrderResource::class);
    }

    public function show()
    {
        $order = $this->clientOrderService->show(request()->route('order'));

        return $this->resourceResponse(new OrderResource($order));
    }

    public function store(OrderRequest $request)
    {
        $this->clientOrderService->store($request->validated());

        return $this->createdResponse(message: translate_success_message('order', 'created'));
    }

    public function cancel($order)
    {
        $this->clientOrderService->cancel($order);
    }
}
