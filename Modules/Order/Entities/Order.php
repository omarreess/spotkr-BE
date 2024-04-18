<?php

namespace Modules\Order\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderFactory;
use Modules\Order\Entities\Builders\OrderBuilder;
use Modules\Order\Traits\OrderRelations;

class Order extends Model
{
    use HasFactory, OrderRelations, PaginationTrait, Searchable;

    protected $fillable = [
        'user_id',
        'activity_id',
        'status',
        'cost',
        'discount',
        'adults_count',
        'children_count',
        'calendar_date',
        'user_details',
        'sessions_count',

    ];

    protected $casts = [
        'user_details' => 'array',
        'adults_count' => 'integer',
        'children_count' => 'integer',
        'sessions_count' => 'integer',
        'cost' => 'double',
        'discount' => 'double',
        'status' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            if(! $model->user_id)
            {
                $model->user_id = auth()->id();
            }
        });
    }

    public static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function newEloquentBuilder($query)
    {
        return new OrderBuilder($query);
    }

    public function cancel()
    {
        
    }
}
