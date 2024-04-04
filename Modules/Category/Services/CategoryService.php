<?php

namespace Modules\Category\Services;

use App\Helpers\PaginationHelper;
use Illuminate\Pagination\LengthAwarePaginator;
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

        if($currentPage == 1) {
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
}
