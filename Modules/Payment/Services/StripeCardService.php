<?php

namespace Modules\Payment\Services;

use App\Exceptions\ValidationErrorsException;
use DB;
use Exception;
use Illuminate\Support\Str;
use Modules\Payment\Entities\Card;
use Modules\Payment\Helpers\StripeExceptionHelper;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeCardService extends StripePaymentService
{
    private Card $cardModel;

    private StripeCustomerService $stripeCustomerService;

    public function __construct()
    {
        parent::__construct();
        $this->stripeCustomerService = new StripeCustomerService();
        $this->cardModel = new Card();
    }

    /**
     * @throws ApiErrorException
     */
    public function index()
    {
        $customerId = $this->stripeCustomerService->getCustomerId();

        if ($customerId) {
            $this->stripeCustomerService->retrieveCustomer($customerId);
            $stripeCreditCards = $this->stripeClient
                ->customers
                ->allSources($customerId, ['object' => 'card']);

            $resultCollection = collect();
            foreach ($stripeCreditCards as $card) {
                $resultCollection[$card->id] = collect([
                    'id' => $card['id'],
                    'cvc_check' => $card['cvc_check'] == 'pass',
                    'number' => Str::mask(
                        Str::random(12),
                        '*',
                        0,
                    ).$card['last4'],
                    'exp_month' => $card['exp_month'],
                    'exp_year' => $card['exp_year'],
                    'brand' => $card['brand'],
                ]);

            }

            $result = collect();
            Card::whereUserId(auth()->id())
                ->get()
                ->each(function ($serverCard) use (&$resultCollection, $result) {
                    if ($resultCollection->has($serverCard->stripe_card_id)) {
                        $result->put(
                            $serverCard->stripe_card_id,
                            $resultCollection->get($serverCard->stripe_card_id)->merge([
                                'id' => $serverCard->id,
                                'active' => (bool) $serverCard->active,
                            ])
                        );
                    }
                });

            return $result->values();
        }

        return false;
    }

    public function retrieve($customerId, $cardId)
    {
        return $this->stripeClient->customers->retrieveSource($customerId, $cardId);
    }

    public function store(array $data)
    {
        //TODO check if the customer exists
        $errors = [];
        $result = $this->stripeCustomerService->createOrRetrieveCustomer();

        if ($result instanceof Customer) {
            self::duplicateCardExists($result->id, $data['source'], $errors);

            if ($errors) {
                return $errors;
            }

            $stripeCreditCard = $this->stripeClient->customers->createSource($result->id, $data);

            $serverCreditCard = auth()->user()->creditCards()->updateOrCreate([
                'stripe_card_id' => $stripeCreditCard->id,
            ], [
                'stripe_card_id' => $stripeCreditCard->id,
                'active' => $result->default_source == null,
            ]);

            $serverCreditCard->cvc_check = $stripeCreditCard->cvc_check;
            $serverCreditCard->exp_month = $stripeCreditCard->exp_month;
            $serverCreditCard->exp_year = $stripeCreditCard->exp_year;
            $serverCreditCard->last4 = $stripeCreditCard->last4;
            $serverCreditCard->brand = $stripeCreditCard->brand;

            return $serverCreditCard;
        }

        return $result;
    }

    /**
     * @throws ApiErrorException
     */
    public function duplicateCardExists($customerId, $sourceToken, array &$errors)
    {
        $allCreditCards = $this->stripeClient->customers->allSources($customerId)->all();
        $sourceCard = $this->stripeClient->tokens->retrieve($sourceToken);

        foreach ($allCreditCards as $card) {
            if ($card->fingerprint == $sourceCard->card->fingerprint) {
                $errors['card'] = translate_error_message('credit_card', 'exists');

                return;
            }
        }
    }

    public function destroy($cardId)
    {
        $card = $this->cardModel::whereId($cardId)->whereUserId(auth()->id())->firstOrFail();
        try {
            DB::transaction(function () use ($card) {
                $this->stripeClient->customers->deleteSource(
                    (new StripeCustomerService())->getCustomerId(),
                    $card->stripe_card_id,
                );

                $card->delete();
            });
        } catch (Exception $e) {
            $errorObject = StripeExceptionHelper::getErrorObject($e);
            if (StripeExceptionHelper::hasErrors($errorObject)) {
                return $errorObject;
            }

            $errors['operation_failed'] = translate_word('operation_failed');

            return $errors;
        }

        return true;
    }

    /**
     * @throws ValidationErrorsException
     */
    public function creditCardExists($creditCardId)
    {
        $serverCreditCard = Card::query()
            ->whereId($creditCardId)
            ->where('user_id', auth()->id())
            ->first();

        if(! $serverCreditCard)
        {
            throw new ValidationErrorsException([
                'credit_card_id' => translate_error_message('credit_card', 'not_exists')
            ]);
        }

        return $serverCreditCard;
    }
}
