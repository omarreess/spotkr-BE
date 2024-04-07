<?php

namespace Modules\Activity\Services;

use App\Exceptions\ValidationErrorsException;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\Activity\Enums\ActivityStatusEnum;
use Modules\Activity\Enums\ActivityTypeEnum;
use Modules\Activity\Http\Controllers\ThirdPartyActivityController;
use Modules\Category\Entities\Category;
use Modules\Country\Entities\City;
use Modules\FcmNotification\Enums\NotificationTypeEnum;
use Modules\FcmNotification\Notifications\FcmNotification;

class ThirdPartyActivityService extends BaseActivityService
{
    public function index()
    {
        return $this->baseIndex()
            ->forCurrentUser()
            ->paginatedCollection();
    }

    public function show($id)
    {
        return $this->baseShow()
            ->forCurrentUser()
            ->findOrFail($id);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function store(array $data)
    {
        $eventOrTrip = in_array($data['type'], [ActivityTypeEnum::EVENT, ActivityTypeEnum::TRIP]);

        //TODO validate the category_id
        $category = Category::query()
            ->when(
                $eventOrTrip,
                fn(Builder $builder)  => $builder->whereNull('parent_id')->whereName($data['type'])
            )
            ->when(! $eventOrTrip, fn(Builder $builder) => $builder->whereNotNull('parent_id')->whereId($data['category_id']))
            ->first();

        if(! $category)
        {
            throw new ValidationErrorsException([
                'category' => translate_error_message('category', 'not_exists'),
            ]);
        }

        //TODO validate city

        $city = City::query()
            ->where('id', $data['city_id'])
            ->first();

        if(! $city)
        {
            throw new ValidationErrorsException([
                'city' => translate_error_message('city', 'not_exists'),
            ]);
        }

        DB::transaction(function() use ($data, $category){
            $activity = $this->activityModel::create($data + [
                    'category_id' => $category->id,
            ]);

            $imageService = new ImageService($activity, $data);

            $imageService->storeMedia(
                ThirdPartyActivityController::MAIN_IMAGE_COLLECTION_NAME,
                'main_image',
            );

            ImageService::addOtherMedias(
                $activity,
                $data,
                ThirdPartyActivityController::OTHER_IMAGES_COLLECTION_NAME,
                'other_images'
            );

            Notification::send(User::query()->whereIsAdmin()->first(), new FcmNotification(
                'activity_created_title',
                'activity_created_body',
                additionalData: [
                    'model_id' => $activity->id,
                    'type' => NotificationTypeEnum::ACTIVITY_CREATED,
                ],
                shouldTranslate: [
                    'title' => true,
                    'body' => true,
                ],
                translatedAttributes: [
                    'activity_created_body' => [
                        'name' => $activity->name,
                    ],
                ],
            ));
        });
    }

    public function destroy($activityId)
    {
        $this->activityModel::query()
            ->forCurrentUser()
            ->findOrFail($activityId)
            ->delete();
    }
}
