<?php

namespace Modules\Activity\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Modules\Activity\Enums\ActivityStatusEnum;
use Modules\FcmNotification\Enums\NotificationTypeEnum;
use Modules\FcmNotification\Notifications\FcmNotification;

class AdminActivityService extends BaseActivityService
{
    public function index($thirdParty)
    {
        return $this
            ->baseIndex()
            ->latest()
            ->where('third_party_id', $thirdParty)
            ->with('thirdParty:id,name')
            ->paginatedCollection();
    }

    public function show($thirdParty, $activity)
    {
        return $this
            ->baseShow()
            ->where('third_party_id', $thirdParty)
            ->with([
                'thirdParty:id,name',
            ])
            ->findOrFail($activity);
    }

    public function changeStatus($status, $thirdParty, $activity)
    {
        $activity = $this->findThirdPartyActivity($thirdParty, $activity);

        $activity->forceFill([
            'status' => $status
                ? ActivityStatusEnum::ACCEPTED
                : ActivityStatusEnum::REJECTED,
        ])->save();

        if($activity->wasChanged())
        {
            Notification::send($activity->thirdParty, new FcmNotification(
                'activity_status_change_title',
                'activity_status_change_body',
                additionalData: [
                    'model_id' => $activity->id,
                    'type' => NotificationTypeEnum::ACTIVITY_STATUS_CHANGED,
                ],
                shouldTranslate: [
                    'title' => true,
                    'body' => true,
                ],
                translatedAttributes: [
                    'activity_status_change_body' => [
                        'name' => $activity->name,
                    ],
                ],
            ));
        }
    }

    public function toggleCarousel($status, $thirdParty, $activity)
    {
        $this->toggleStatus($thirdParty, $activity, $status);
    }

    public function toggleAdrenalineRush($status, $thirdParty, $activity): void
    {
        $this->toggleStatus(
            $thirdParty,
            $activity,
            $status,
            'include_in_adrenaline_rush',
        );
    }

    private function toggleStatus($thirdParty, $activity, $status, string $columnName = 'include_in_carousel'): void
    {
        $activity = $this->findThirdPartyActivity($thirdParty, $activity);

        $activity->forceFill([
            $columnName => $status,
        ])
            ->save();
    }

    private function findThirdPartyActivity($thirdParty, $activity)
    {
        return $this->activityModel::query()
            ->where('third_party_id', $thirdParty)
            ->findOrFail($activity);
    }
}
