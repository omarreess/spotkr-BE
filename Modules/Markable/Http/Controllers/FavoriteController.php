<?php

namespace Modules\Markable\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Enums\ActivityStatusEnum;
use Modules\Markable\Helpers\FavoriteHelper;
use Modules\Markable\Http\Requests\FavoriteToggleRequest;

class FavoriteController extends Controller
{
    use HttpResponse;

    public static array $allowedTypes = [
        'activity' => Activity::class,
    ];

    public function __invoke(FavoriteToggleRequest $request)
    {
        $modelType = $request->input('model_type');
        $modelID = $request->input('model_id');
        $errors = [];

        $modelBuilder = (static::$allowedTypes[$modelType])::query()->whereId($modelID);

        if ($modelType == 'activity') {
            $modelBuilder = $modelBuilder->where('status', ActivityStatusEnum::ACCEPTED);
        }

        $modelBuilder = $modelBuilder->firstOr(function () use (&$errors) {
            $errors['model_id'] = translate_error_message('model', 'not_exists');
        });

        if ($modelBuilder) {
            FavoriteHelper::model()::toggle($modelBuilder, auth()->user());

            return $this->okResponse(
                message: translate_success_message('model', 'toggled')
            );
        }

        return $this->validationErrorsResponse($errors);
    }
}
