<?php

namespace Modules\LeaderBoard\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Modules\Auth\Transformers\UserResource;
use Modules\LeaderBoard\Http\Requests\LeaderboardFilterRequest;
use Modules\LeaderBoard\Services\ClientLeaderBoardService;

class ClientLeaderBoardController extends Controller
{
    use HttpResponse;

    private ClientLeaderBoardService $clientLeaderBoardService;

    public function __construct(ClientLeaderBoardService $clientLeaderBoardService)
    {
        $this->clientLeaderBoardService = $clientLeaderBoardService;
    }

    public function index(LeaderboardFilterRequest $request)
    {
        $leaderBoards = $this->clientLeaderBoardService->index($request->validated());

        return $this->paginatedResponse($leaderBoards, UserResource::class);
    }
}
