<?php

namespace Modules\Activity\Transformers;

use App\Helpers\DateHelper;
use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Modules\Auth\Transformers\UserResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Country\Transformers\CityResource;
use Modules\Markable\Helpers\FavoriteHelper;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'type' => $this->whenHas('type'),
            'favorite' => $this->whenNotNull(FavoriteHelper::resourceFavorite($this)),
            'status' => $this->whenHas('status'),
            'include_in_carousel' => $this->whenHas('include_in_carousel'),
            'include_in_adrenaline_rush' => $this->whenHas('include_in_adrenaline_rush'),
            'rating_average' => $this->whenHas('rating_average'),
            'price' => $this->whenHas('price'),
            'discount' => $this->whenHas('discount'),
            'phone' => $this->whenHas('phone'),
            'fax' => $this->whenHas('fax'),
            'email' => $this->whenHas('email'),
            'website' => $this->whenHas('website'),
            'main_image' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'mainImage')),
            'hold_on' => $this->whenHas('hold_at', Carbon::parse($this->hold_on)->format(DateHelper::defaultDateTimeFormat())),
            'created_at' => $this->whenHas('created_at', fn() => ! $this->created_at ? null : Carbon::parse($this->created_at)->format(DateHelper::amPmFormat())),
            'description' => $this->whenHas('description'),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'address' => $this->whenHas('address'),
            'features' => $this->whenHas('features'),
            'social_links' => $this->whenHas('social_links'),
            'city' => CityResource::make($this->whenLoaded('city')),
            'third_party' => UserResource::make($this->whenLoaded('thirdParty')),
            'other_images' => $this->whenNotNull(ResourceHelper::getImagesObject($this, 'otherImages', shouldReturnDefault: false)),
            'basic_city' => $this->whenLoaded('basicCity'),
            'category_ids' => $this->whenLoaded('basicCategory', function(){
                $basicCategory = $this->basicCategory;
                $parentId = $basicCategory?->parentCategory?->parent_id;
                $subCategory = $basicCategory?->parentCategory?->id;
                $subSubCategory = $basicCategory->id;

                if(! $parentId)
                {
                    return [
                        'parent_category' => $subSubCategory,
                        'sub_category' => null,
                        'sub_sub_category' => null,
                    ];
                }

                return [
                    'parent_category' => $parentId,
                    'sub_category' => $subCategory,
                    'sub_sub_category' => $subSubCategory,
                ];
            }),
            'open_times' => $this->whenHas('open_times'),
            'course_bundles' => $this->whenHas('course_bundles'),
        ];
    }
}
