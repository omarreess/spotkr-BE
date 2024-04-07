<?php

namespace Modules\Activity\Services;

class ClientActivityService extends BaseActivityService
{
    public function index()
    {
        return $this
            ->baseIndex()
            ->whereApproved()
            ->getFavorites()
            ->paginatedCollection();
    }

    public function show($activity)
    {
        return $this
            ->baseShow()
            ->whereApproved()
            ->getFavorites()
            ->with([
                'thirdParty' => fn($query) => $query->select(['id', 'name', 'phone', 'email'])->with('avatar'),
                'category:id,name',
            ])
            ->findOrFail($activity);
    }
}
