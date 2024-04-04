<?php

namespace Modules\Category\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryResource;

class ParentCategoryController extends Controller
{
    use HttpResponse;

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function parentCategories()
    {
        $categories = $this->categoryService->homeParentCategories();

        return $this->paginatedResponse($categories, CategoryResource::class);
    }

    public function subCategories($parentCategory)
    {
        $subCategories = $this->categoryService->subCategories($parentCategory);

        return $this->paginatedResponse($subCategories, CategoryResource::class);
    }
}
