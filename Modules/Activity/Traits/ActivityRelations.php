<?php

namespace Modules\Activity\Traits;

use App\Helpers\MediaHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\Http\Controllers\ThirdPartyActivityController;
use Modules\Category\Entities\Category;
use Modules\Country\Entities\City;
use Modules\Review\Traits\ReviewRelation;

trait ActivityRelations
{
    use ReviewRelation;

    public function thirdParty(): BelongsTo
    {
        return $this->belongsTo(User::class, 'third_party_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function basicCity(): BelongsTo
    {
        return $this->city()->select(['id', 'country_id']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function basicCategory(): BelongsTo
    {
        return $this->category()->select(['id', 'parent_id'])->with([
            'parentCategory' => fn($query) => $query->select(['id', 'parent_id']),
        ]);
    }

    public function mainImage()
    {
        return MediaHelper::mediaRelationship(
            $this,
            ThirdPartyActivityController::MAIN_IMAGE_COLLECTION_NAME
        );
    }

    public function otherImages()
    {
        return MediaHelper::mediaRelationship(
            $this,
            ThirdPartyActivityController::OTHER_IMAGES_COLLECTION_NAME,
        );
    }
}
