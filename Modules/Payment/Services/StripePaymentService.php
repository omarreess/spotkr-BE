<?php

namespace Modules\Payment\Services;

use Stripe\StripeClient;

class StripePaymentService
{
    protected StripeClient $stripeClient;

    public static string $stripeCustomerIdColumn = 'stripe_customer_id';

    public function __construct()
    {
        $this->stripeClient = new StripeClient(config('services.stripe.secret'));
    }

    public function balance()
    {
        return $this->stripeClient->balance->retrieve();
    }
}
