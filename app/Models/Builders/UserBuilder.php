<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Modules\Activity\Enums\ActivityTypeEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Traits\AuthTrait;
use Modules\Auth\Traits\VerificationBuilderTrait;

class UserBuilder extends Builder
{
    use AuthTrait, VerificationBuilderTrait;

    public function whereIsThirdParty(): UserBuilder
    {
        return $this->where('type', UserTypeEnum::THIRD_PARTY);
    }

    public function whereIsAdmin(): static
    {
        $this->whereType(UserTypeEnum::ADMIN);

        return $this;
    }

    public function leaderboardFilters(array $filters = [])
    {
        $filters = array_filter($filters);
        $country = $filters['country_id'] ?? null;
        $subSubCategory = $filters['sub_sub_category_id'] ?? null;
        $subCategory = $filters['sub_category_id'] ?? null;

        $this
            ->when($country, fn($query) => $query->where('country_id', $country))
            ->filterOrderCategories([$subCategory, $subSubCategory]);

        return $this;
    }

    public function filterOrderCategories(array $filters)
    {
        $this->when($filters[0] || $filters[1], function (Builder $builder) use ($filters){
            $builder->whereHas('orders', function($builder) use ($filters){
                $builder->whereHas('activity', function (Builder $builder) use ($filters){
                    [$subCategory, $subSubCategory] = $filters;
                    $builder
                        ->where('type', ActivityTypeEnum::SPORT)
                        ->when(! is_null($subSubCategory), function($query) use ($subCategory, $subSubCategory){
                            $query
                                ->where('category_id', $subSubCategory)
                                ->when(
                                    $subCategory,
                                    fn($query2) => $query2->whereHas(
                                        'category',
                                        fn($query3) => $query3
                                            ->where('id', $subSubCategory)
                                            ->whereHas(
                                                'parentCategory',
                                                fn($query4) => $query4->where('id', $subCategory)
                                            )
                                    )
                                );
                        });
                });
            });
        });
    }
}
