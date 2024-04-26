<?php

namespace Modules\Order\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Order\Http\Requests\OrderPaymentRequest;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Services\ClientOrderService;
use Modules\Order\Transformers\OrderResource;
use Modules\Review\Http\Requests\ReviewRequest;
use Modules\Review\Services\ReviewService;

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

        return $this->okResponse(message: translate_success_message('order', 'canceled'));
    }

    public function pay(OrderPaymentRequest $request, $order): JsonResponse
    {
        $this->clientOrderService->payOrder($request->credit_card_id, $order);

        return $this->okResponse(message: translate_success_message('order', 'paid'));
    }

    public function review(ReviewRequest $request, $order, ReviewService $reviewService)
    {
        $this->clientOrderService->review(
            $request->validated(),
            $order,
            $reviewService
        );

        return $this->okResponse(message: translate_success_message('order', 'reviewed'));
    }
}
