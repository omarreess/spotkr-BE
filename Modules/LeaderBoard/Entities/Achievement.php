<?php

namespace Modules\LeaderBoard\Entities;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Achievement extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if(! $model->user_id)
            {
                $model->user_id = auth()->id();
            }
        });
    }

    protected $fillable = [
        'title',
        'required_points',
        'gained_points',
    ];

    public function icon()
    {
        return MediaHelper::mediaRelationship($this, 'achievement_icon');
    }
}
