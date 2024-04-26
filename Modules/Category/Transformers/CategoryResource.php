<?php

namespace Modules\Category\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'icon')),
            'image' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'image')),
            'is_parent_category' => $this->whenHas('parent_id', function () {
                return $this->parent_id == null;
            }),
            'parent_category' => CategoryResource::make($this->whenLoaded('parentCategory')),
        ];
    }
}
