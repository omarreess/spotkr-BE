<?php

namespace Modules\Payment\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Card;
use Modules\Payment\Helpers\StripeExceptionHelper;
use Modules\Payment\Http\Requests\StoreCardRequest;
use Modules\Payment\Services\StripeCardService;
use Modules\Payment\Transformers\CardResource;

class CardController extends Controller
{
    use HttpResponse;

    private StripeCardService $stripeCardService;

    public function __construct()
    {
        $this->stripeCardService = new StripeCardService();
    }

    public function index()
    {
        $response = [];

        try {
            $result = $this->stripeCardService->index();

            if ($result) {
                return $this->resourceResponse($result);
            }
        } catch (\Exception $e) {
        }

        return $this->resourceResponse($response);
    }

    public function store(StoreCardRequest $request)
    {
        $result = $this->stripeCardService->store($request->validated());

        if ($result instanceof Card) {
            return $this->createdResponse(new CardResource($result));
        } elseif (StripeExceptionHelper::hasErrors($result)) {
            return (new StripeExceptionHelper())->returnStripeError($result);
        }

        return $this->validationErrorsResponse($result);
    }

    public function destroy($cardId)
    {
        $result = $this->stripeCardService->destroy($cardId);

        if (is_bool($result)) {
            return $this->okResponse(message: translate_success_message('credit_card', 'deleted'));
        }

        if (StripeExceptionHelper::hasErrors($result)) {
            return (new StripeExceptionHelper())->returnStripeError($result);
        }

        return $this->validationErrorsResponse($result);
    }
}
