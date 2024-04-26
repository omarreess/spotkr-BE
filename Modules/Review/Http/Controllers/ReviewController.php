<?php

namespace Modules\Review\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Database\Eloquent\Builder;
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
        $activity = $this->getActivity($activity);

        return $this->getModelReviews($activity);
    }

    public function activityTotal($activity)
    {
        $activity = $this->getActivity($activity);

        return $this->resourceResponse([
            'count' => $activity->reviews()->count(),
        ]);
    }

    private function getModelReviews(ReviewableContract $model)
    {
        return $this->paginatedResponse($model
            ->reviews()
            ->latest()
            ->with([
                'user' => fn ($builder) => $builder
                    ->select(['id', 'name'])
                    ->with('avatar'),
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

        $model->recalculateRating();

        return $this->okResponse(message: translate_success_message('model', 'reviewed'));
    }

    public function getModelByType(string $reviewableType, $modelId): ?ReviewableContract
    {
        return match ($reviewableType) {
            ReviewTypeEnum::ACTIVITY => Activity::query()
                ->whereApproved()
                ->findOrFail($modelId, ['id']),
            default => null,
        };
    }

    private function getActivity($activityId)
    {
        $userType = auth()->user()?->type;

        return Activity::query()
            ->whereApproved()
            ->when($userType == UserTypeEnum::THIRD_PARTY, fn (Builder $builder) => $builder->where('third_party_id', auth()->id()))
            ->findOrFail($activityId, ['id']);
    }
}
