<?php

namespace Modules\Payment\Entities;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'user_id',
        'stripe_card_id',
        'active',
    ];
}
