<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCategoriesRequest;
use App\Http\Requests\GetCategoryWithImagesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryWithImagesResource;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CategoryController
{
    public function index(GetCategoriesRequest $request): AnonymousResourceCollection
    {
        $categories = Category::simplePaginate(
            perPage: $request->input('limit', Category::PAGINATION_LIMIT),
            columns: ['id', 'name', 'slug'],
            page: $request->input('page', 1)
        );
        $categories->withQueryString();

        return CategoryResource::collection($categories);
    }

    public function show(GetCategoryWithImagesRequest $request, string $slug): CategoryWithImagesResource
    {
        $category = Category::with([
            'images' => fn(BelongsToMany $q) => $q->latest()
                ->limit($request->input('limit', Image::PAGINATION_LIMIT))])
            ->where('slug', $slug)
            ->firstOrFail();

        return new CategoryWithImagesResource($category);
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->name = $request->input('name');
        $category->save();

        return new CategoryResource($category);
    }
}
