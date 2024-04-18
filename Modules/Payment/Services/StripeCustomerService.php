<?php

namespace Modules\Payment\Services;

use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeCustomerService extends StripePaymentService
{
    public function customerExists($user = null): bool
    {
        $user = $user ?: auth()->user();

        return ! is_null($this->customerExists($user));
    }

    /**
     * @throws ApiErrorException
     */
    public function createOrRetrieveCustomer($customerId = null, $user = null): array|Customer
    {
        $customerId = $customerId ?: $this->getCustomerId();
        $user = $user ?: auth()->user();

        if (! $customerId) {
            $customer = $this->storeCustomer($user);
        } else {
            $customer = $this->retrieveCustomer($customerId);
        }

        if ($customer->deleted) {
            $customer = $this->storeCustomer($user);
        }

        return $customer;
    }

    public function getCustomerId($user = null)
    {
        $user = $user ?: auth()->user();

        return $user->{self::$stripeCustomerIdColumn};
    }

    public function createCustomer(array $data)
    {
        return $this->stripeClient->customers->create($data);
    }

    /**
     * @throws ApiErrorException
     */
    public function retrieveCustomer($customerId = null)
    {
        $customerId = $customerId ?: $this->getCustomerId();

        return $this->stripeClient->customers->retrieve($customerId);
    }

    private function storeCustomer($user)
    {
        $customer = $this->createCustomer([
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone,
        ]);

        $user->forceFill([StripePaymentService::$stripeCustomerIdColumn => $customer->id]);
        $user->save();

        return $customer;
    }
}
