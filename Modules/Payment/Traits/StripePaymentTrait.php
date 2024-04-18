<?php

namespace Modules\Payment\Traits;

use Modules\Payment\Services\StripePaymentService;

trait StripePaymentTrait
{
    public function addStripeCustomerIdToFillable()
    {
        if (! in_array(StripePaymentService::$stripeCustomerIdColumn, $this->fillable)) {
            $this->fillable[] = StripePaymentService::$stripeCustomerIdColumn;
        }
    }
}
