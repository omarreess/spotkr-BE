<?php

namespace Modules\Order\Services;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThirdPartyOrderService extends BaseOrderService
{
    public function index()
    {
        return $this
            ->baseIndex()
            ->whereHas('activity', fn (BelongsTo $builder) => $builder->where('user_id', auth()->id()))
            ->with([
                'activity' => fn ($builder) => $builder->select([
                    'id',
                    'name',
                    'type',
                ])
                    ->with('mainImage'),
            ])
            ->select(['id', 'status', 'created_at']);
    }

    public function show($id)
    {
        $this->orderModel::query()
            ->whereHas('activity', fn (BelongsTo $builder) => $builder->where('user_id', auth()->id()))
            ->with([
                'activity' => fn ($builder) => $builder->select([
                    'id',
                    'name',
                    'type',
                ])
                    ->with('mainImage'),
            ])
            ->findOrFail($id);
    }
}
