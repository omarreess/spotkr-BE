<?php

namespace Modules\Coupon\Transformers;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'discount' => $this->discount,
            'status' => $this->status,
            'number_of_users' => $this->number_of_users,
            'used_by_users'  => $this->used_by_users,
            'valid_till' => $this->whenHas(
                'valid_till',
                fn() => is_null($this->valid_till)
                    ? null
                    : Carbon::parse($this->valid_till)->format(DateHelper::amPmFormat())
            ),
            'created_at' => Carbon::parse($this->created_at)->format(DateHelper::amPmFormat())
        ];
    }
}
