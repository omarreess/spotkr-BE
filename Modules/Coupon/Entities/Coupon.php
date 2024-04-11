<?php

namespace Modules\Coupon\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property int|null $number_of_users
 * @property int|null $used_by_users
 * @property float $discount percentage
 * @property bool $status
 * @property string|null $valid_till
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon formatResult()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon paginatedCollection()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereNumberOfUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUsedByUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereValidTill($value)
 * @mixin \Eloquent
 */
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
