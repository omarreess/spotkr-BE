<?php

namespace Modules\Category\Services;

use App\Exceptions\ValidationErrorsException;
use App\Helpers\PaginationHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Activity\Enums\ActivityTypeEnum;
use Modules\Category\Entities\Category;

class CategoryService
{
    private Category $categoryModel;

    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function homeParentCategories()
    {
        $currentPage = PaginationHelper::getCurrentPage();

        if ($currentPage == 1) {
            $parentCategories = $this->categoryModel::query()
                ->whereParentCategory()
                ->with([
                    'image',
                    'icon',
                ])
                ->whereIsNotParentSport()
                ->get();
        } else {
            $parentCategories = collect();
        }

        $sportSubCategories = $this->categoryModel::query()
            ->whereParentIsSport()
            ->with([
                'image',
                'icon',
            ])
            ->paginatedCollection();

        $items = $sportSubCategories->items();
        $items = $parentCategories->merge($items);

        return new LengthAwarePaginator(
            $items,
            $sportSubCategories->total(),
            $sportSubCategories->perPage(),
            $currentPage,
        );
    }

    public function subCategories($parentCategory)
    {
        return $this->categoryModel::query()
            ->where('parent_id', $parentCategory)
            ->whereNotNull('parent_id')
            ->with([
                'image',
                'icon',
            ])
            ->paginatedCollection();
    }

    /**
     * @throws ValidationErrorsException
     */
    public function categoryBasedTypeExists($categoryId, $activityType)
    {
        $eventOrTrip = in_array($activityType, [ActivityTypeEnum::EVENT, ActivityTypeEnum::TRIP]);

        $category = Category::query()
            ->when(
                $eventOrTrip,
                fn (Builder $builder) => $builder->whereNull('parent_id')->whereName($activityType)
            )
            ->when(
                ! $eventOrTrip,
                fn (Builder $builder) => $builder
                    ->whereNotNull('parent_id')
                    ->whereHas(
                        'parentCategory',
                        fn (Builder $query) => $query->whereHas('parentCategory', fn (Builder $innerQuery) => $innerQuery->whereName($activityType))
                    )
                    ->whereId($categoryId)
            )
            ->first();

        if (! $category) {
            throw new ValidationErrorsException([
                'category' => translate_error_message('category', 'not_exists'),
            ]);
        }

        return $category;
    }
}
