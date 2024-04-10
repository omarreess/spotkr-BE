<?php

namespace Modules\Review\Transformers;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Modules\Auth\Transformers\UserResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->whenHas('description'),
            'rating' => $this->whenHas('rating'),
            'created_at' => Carbon::parse($this->created_at)->format(DateHelper::defaultDateFormat()),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
