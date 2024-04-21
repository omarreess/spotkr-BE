<?php

namespace Modules\LeaderBoard\Services;

class ClientLeaderBoardService extends BaseLeaderBoardService
{
    public function index(array $filters)
    {
        return $this->baseIndex($filters)->paginatedCollection();
    }
}
