<?php

namespace Modules\Order\Transformers;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Modules\Activity\Transformers\ActivityResource;

class OrderResource extends JsonResource
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
            'status' => $this->status,
            'cost' => $this->cost,
            //            'discount' => $this->discount,
            'adults_count' => $this->adults_count,
            'children_count' => $this->children_count,
            'calendar_date' => $this->calendar_date,
            'sessions_count' => $this->sessions_count,
            'created_at' => Carbon::parse($this->created_at)->format(DateHelper::amPmFormat()),
            'user_details' => $this->user_details,
            'activity' => ActivityResource::make($this->whenLoaded('activity')),
        ];
    }
}
