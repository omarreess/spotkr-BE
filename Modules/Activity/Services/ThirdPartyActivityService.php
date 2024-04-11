<?php

namespace Modules\Activity\Services;

use App\Exceptions\ValidationErrorsException;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Http\Controllers\ThirdPartyActivityController;
use Modules\Category\Services\CategoryService;
use Modules\Country\Services\CityService;
use Modules\FcmNotification\Enums\NotificationTypeEnum;
use Modules\FcmNotification\Notifications\FcmNotification;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ThirdPartyActivityService extends BaseActivityService
{
    private CityService $cityService;
    private CategoryService $categoryService;

    public function __construct(
        Activity $activityModel,
        CityService $cityService,
        CategoryService $categoryService,
    )
    {
        parent::__construct($activityModel);

        $this->cityService = $cityService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return $this->baseIndex()
            ->latest()
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
        $this->storeOrUpdate($data);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function update(array $data, $id)
    {
        $this->storeOrUpdate($data, $id);
    }

    public function destroy($activityId)
    {
        $this->activityModel::query()
            ->forCurrentUser()
            ->findOrFail($activityId)
            ->delete();
    }

    /**
     * @throws ValidationErrorsException
     */
    private function storeOrUpdate(array $data, $id = null): void
    {
        $inUpdate = ! is_null($id);

        if($inUpdate)
        {
            $activity = $this->activityModel::query()
                ->forCurrentUser()
                ->findOrFail($id);
        }

        $category = $this->categoryService->categoryBasedTypeExists($data['category_id'] ?? null, $data['type']);

        $this->cityService->cityExists($data['city_id']);

        $this->removeUnRelatedFields($data);

        $activity = $activity ?? null;

        $activity = DB::transaction(function() use ($data, $category, $inUpdate, $activity){
            if(! $inUpdate)
            {
                $activity = $this->activityModel::create($data + ['category_id' => $category->id]);
            } else {
                $activity->update($data + ['category_id' => $category->id]);
            }

            $this->syncMedia($inUpdate, $activity, $data);

            return $activity;
        });

        if($inUpdate)
        {
            $this->activityUpdatedNotification($activity);
        }
         else {
             $this->activityCreatedNotification($activity);
         }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function syncMedia(bool $inUpdate, Activity $activity, array $data): void
    {
        $imageService = ImageService::createInstance($activity, $data);

        if($inUpdate)
        {
            $imageService->updateOneMedia(
                ThirdPartyActivityController::MAIN_IMAGE_COLLECTION_NAME,
                'main_image',
            );

            $imageService->updateMultipleMedia(
                ThirdPartyActivityController::OTHER_IMAGES_COLLECTION_NAME,
                'deleted_images_ids',
                'other_images',
            );

        } else {
            $imageService->storeOneMediaFromRequest(
                ThirdPartyActivityController::MAIN_IMAGE_COLLECTION_NAME,
                'main_image',
            );

            $imageService->storeMultipleMedia(
                ThirdPartyActivityController::OTHER_IMAGES_COLLECTION_NAME
            );
        }
    }

    public function activityCreatedNotification(Activity $activity)
    {
        $this->sendActivityNotification($activity);
    }

    public function activityUpdatedNotification(Activity $activity)
    {
        if($activity->wasChanged()) {
            $this->sendActivityNotification($activity, true);
        }
    }

    private function sendActivityNotification(Activity $activity, bool $inUpdate = false): void
    {
        $title = $inUpdate ? 'activity_updated_title' : 'activity_created_title';
        $body = $inUpdate ? 'activity_updated_body' : 'activity_created_body';
        $notificationType = $inUpdate ? NotificationTypeEnum::ACTIVITY_UPDATED : NotificationTypeEnum::ACTIVITY_CREATED;

        Notification::send(User::query()->whereIsAdmin()->first(), new FcmNotification(
            $title,
            $body,
            additionalData: [
                'model_id' => $activity->id,
                'type' => $notificationType,
            ],
            shouldTranslate: [
                'title' => true,
                'body' => true,
            ],
            translatedAttributes: [
                $body => [
                    'name' => $activity->name,
                ],
            ],
        ));
    }

    private function removeUnRelatedFields(array &$data): void
    {
        if(! isset($data['hold_on']))
        {
            $data['hold_on'] = null;
        }

        if(! isset($data['open_times']))
        {
            $data['open_times'] = null;
        }

        if(! isset($data['course_bundles']))
        {
            $data['course_bundles'] = null;
        }
    }
}
