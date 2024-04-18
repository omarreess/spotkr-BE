<?php

namespace Modules\Payment\Services;

class StripeChargeService extends StripePaymentService
{
    public function store($stripeCardId, $stripeCustomerId, $totalPrice, $description = null, array $metaData = [])
    {
        $this->stripeClient->charges->create([
            'card' => $stripeCardId,
            'customer' => $stripeCustomerId,
            'currency' => 'USD',
            'amount' => ($totalPrice * 100),
            'description' => $description,
            'metadata' => $metaData,
        ]);
    }
}
