<?php

namespace Modules\Activity\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Activity\Http\Requests\ActivityRequest;
use Modules\Activity\Services\ThirdPartyActivityService;
use Modules\Activity\Transformers\ActivityResource;

class ThirdPartyActivityController extends Controller
{
    use HttpResponse;

    const MAIN_IMAGE_COLLECTION_NAME = 'activity_main_image';

    const OTHER_IMAGES_COLLECTION_NAME = 'activity_other_image';

    private ThirdPartyActivityService $thirdPartyActivityService;

    public function __construct(ThirdPartyActivityService $thirdPartyActivityService)
    {
        $this->thirdPartyActivityService = $thirdPartyActivityService;
    }

    public function index(): JsonResponse
    {
        $activities = $this->thirdPartyActivityService->index();

        return $this->paginatedResponse($activities, ActivityResource::class);
    }

    public function show($id): JsonResponse
    {
        $activity = $this->thirdPartyActivityService->show($id);

        return $this->resourceResponse(ActivityResource::make($activity));
    }

    public function store(ActivityRequest $request)
    {
        $this->thirdPartyActivityService->store($request->validated());

        return $this->createdResponse(message: translate_success_message('activity', 'created'));
    }

    public function update(ActivityRequest $request, $activity)
    {
        $this->thirdPartyActivityService->update($request->validated(), $activity);

        return $this->okResponse(message: translate_success_message('activity', 'updated'));
    }

    public function destroy($activity)
    {
        $this->thirdPartyActivityService->destroy($activity);

        return $this->okResponse(message: translate_success_message('activity', 'deleted'));
    }
}
