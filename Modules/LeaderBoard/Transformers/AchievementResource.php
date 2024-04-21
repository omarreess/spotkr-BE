<?php

namespace Modules\LeaderBoard\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'required_points' => $this->whenHas('required_points'),
            'gained_points' => $this->whenHas('gained_points'),
            'icon' => ResourceHelper::getFirstMediaOriginalUrl($this, 'icon'),
            'finished' => $this->when(! is_null($this->required_points) && ! is_null($this->gained_points), function(){
                return $this->gained_points >= $this->required_points;
            })
        ];
    }
}
