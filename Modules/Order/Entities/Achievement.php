<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public static function newFactory()
    {
        return \Modules\Order\Database\factories\AchievementFactory::new();
    }
}
