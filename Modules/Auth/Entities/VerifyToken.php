<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'handle',
        'code',
        'expires_at',
        'type',
    ];
}
