<?php

namespace Modules\Payment\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Payment\Services\StripePaymentService;

class StripePaymentController extends Controller
{
    use HttpResponse;

    public function __construct(private StripePaymentService $paymentService)
    {
    }

    public function index()
    {
        return $this->paymentService->balance();
    }
}
