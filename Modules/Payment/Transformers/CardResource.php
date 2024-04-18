<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CardResource extends JsonResource
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
            'number' => Str::mask(
                Str::random(12),
                '*',
                0,
            ).$this->last4,
            'valid_cvc' => $this->cvc_check == 'pass',
            'exp_year' => $this->exp_year,
            'brand' => $this->whenHas('brand'),
            'exp_month' => $this->exp_month,
            'active' => $this->whenHas('active', (bool) $this->active),
        ];
    }
}
