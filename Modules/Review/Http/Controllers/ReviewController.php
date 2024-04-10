<?php

namespace Modules\Review\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Activity\Entities\Activity;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Review\Contracts\ReviewableContract;
use Modules\Review\Enums\ReviewTypeEnum;
use Modules\Review\Http\Requests\ReviewRequest;
use Modules\Review\Transformers\ReviewResource;

class ReviewController extends Controller
{
    use HttpResponse;

    public function activity($activity)
    {
        $userType = auth()->user()?->type;

        $activity = Activity::query()
            ->whereApproved()
            ->when($userType == UserTypeEnum::THIRD_PARTY, fn(Builder $builder) => $builder->where('third_party_id', auth()->id()))
            ->findOrFail($activity, ['id']);

        return $this->getModelReviews($activity);
    }

    private function getModelReviews(ReviewableContract $model)
    {
        return $this->paginatedResponse($model
            ->reviews()
            ->latest()
            ->with([
                'user' => fn($builder) => $builder
                    ->select(['id', 'name'])
                    ->with('avatar')
            ])
            ->paginatedCollection(),
            ReviewResource::class,
        );
    }

    public function store(ReviewRequest $request)
    {
        $model = $this->getModelByType($request->reviewable_type, $request->reviewable_id);

        $validData = $request->validated();
        $validData['reviewable_type'] = get_class($model);

        $model->reviews()->updateOrCreate([
            'reviewable_type' => get_class($model),
            'reviewable_id' => $model->id,
        ],
            $validData
        );

        return $this->okResponse(message: translate_success_message('model', 'reviewed'));
    }

    public function getModelByType(string $reviewableType, $modelId)
    {
        return match($reviewableType) {
            ReviewTypeEnum::ACTIVITY => Activity::query()
                ->whereApproved()
                ->findOrFail($modelId, ['id']),
            default => null,
        };
    }
}
