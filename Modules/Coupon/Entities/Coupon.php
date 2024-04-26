<?php

namespace Modules\Coupon\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, PaginationTrait, Searchable;

    protected $fillable = [
        'code',
        'number_of_users',
        'used_by_users',
        'discount',
        'status',
        'valid_till',
    ];

    protected $casts = [
        'status' => 'boolean',
        'discount' => 'double',
    ];
}
