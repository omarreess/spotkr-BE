<?php

namespace Modules\Activity\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Activity\Http\Requests\StatusRequest;
use Modules\Activity\Services\AdminActivityService;
use Modules\Activity\Transformers\ActivityResource;

class AdminActivityController extends Controller
{
    use HttpResponse;

    private AdminActivityService $activityService;

    public function __construct(AdminActivityService $adminActivityService)
    {
        $this->activityService = $adminActivityService;
    }

    public function index($thirdParty)
    {
        $activities = $this->activityService->index($thirdParty);

        return $this->paginatedResponse($activities, ActivityResource::class);
    }

    public function show($thirdParty, $activity)
    {
        $activity = $this->activityService->show($thirdParty, $activity);

        return $this->resourceResponse(ActivityResource::make($activity));
    }

    public function changeStatus(StatusRequest $request, $thirdParty, $activity): JsonResponse
    {
        $this->activityService->changeStatus($request->status, $thirdParty, $activity);

        return $this->okResponse(message: translate_success_message('activity', 'updated'));
    }

    public function toggleCarousel(StatusRequest $request, $thirdParty, $activity)
    {
        $this->activityService->toggleCarousel($request->status, $thirdParty, $activity);

        return $this->okResponse(message: translate_success_message('activity', 'updated'));
    }

    public function toggleAdrenalineRush(StatusRequest $request, $thirdParty, $activity)
    {
        $this->activityService->toggleAdrenalineRush($request->status, $thirdParty, $activity);

        return $this->okResponse(message: translate_success_message('activity', 'updated'));
    }
}
