<?php

namespace Modules\Activity\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Activity\Services\ClientActivityService;
use Modules\Activity\Transformers\ActivityResource;

class ClientActivityController extends Controller
{
    use HttpResponse;

    private ClientActivityService $clientActivityService;

    public function __construct(ClientActivityService $clientActivityService)
    {
        RequestHelper::loginIfHasToken($this);
        $this->clientActivityService = $clientActivityService;
    }

    public function index(): JsonResponse
    {
        $activities = $this->clientActivityService->index();

        return $this->paginatedResponse($activities, ActivityResource::class);
    }

    public function show($activity): JsonResponse
    {
        $activity = $this->clientActivityService->show($activity);

        return $this->resourceResponse(ActivityResource::make($activity));
    }

    public function similar($activity)
    {
        $similarActivities = $this->clientActivityService->similar($activity);

        return $this->resourceResponse(ActivityResource::collection($similarActivities));
    }

    public function moreExperience($activity)
    {
        $moreExperience = $this->clientActivityService->moreExperience($activity);

        return $this->resourceResponse(ActivityResource::collection($moreExperience));
    }

    public function carousel()
    {
        $carousel = $this->clientActivityService->carousel();

        return $this->paginatedResponse($carousel, ActivityResource::class);
    }

    public function adrenalineRush()
    {
        $adrenalineRush = $this->clientActivityService->adrenalineRush();

        return $this->paginatedResponse($adrenalineRush, ActivityResource::class);
    }
}
