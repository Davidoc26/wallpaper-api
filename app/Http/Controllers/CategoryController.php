<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCategoriesRequest;
use App\Http\Requests\GetCategoryWithImagesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryWithImagesResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

final class CategoryController
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     description="Get list of categories",
     *     @OA\Parameter(
     *          in="query",
     *          description="categories per page",
     *          name="limit",
     *          required=false,
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="page",
     *     ),
     *     @OA\Response(response="200", description="returns list of categories",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      ref="#/components/schemas/CategoryResource",
     *                  ),
     *              ),
     *         ),
     *     ),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/categories/{slug}",
     *     summary="Show category with images",
     *     @OA\Parameter(
     *          in="path",
     *          name="slug",
     *          required=true,
     *          description="category slug",
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="limit",
     *          description="images per page",
     *          example="3",
     *          required=false,
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="page",
     *          example="1",
     *          required=false,
     *     ),
     *     @OA\Response(response="422", description="validation failed", @OA\JsonContent()),
     *     @OA\Response(response="200", description="successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/CategoryWithImagesResource",
     *               ),
     *          ),
     *     ),
     * )
     */
    public function show(GetCategoryWithImagesRequest $request, string $slug): CategoryWithImagesResource
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->setRelation('images', $category->images()
            ->simplePaginate(
                perPage: $request->input('limit'),
                page: $request->input('page')
            )
        );

        return new CategoryWithImagesResource($category);
    }

    /**
     * @OA\Post(
     *      path="/api/categories",
     *      description="Create a new category",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="name",type="string",example="Category name"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Returns created category",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/CategoryResource",
     *          ),
     *      ),
     *      @OA\Response(response="422", description="validation failed", @OA\JsonContent()),
     * ),
     */
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * @OA\Patch(
     *     path="/api/categories/{slug}",
     *     description="Update category",
     *      @OA\Parameter (
     *           in="path",
     *           name="slug",
     *           description="Category slug",
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="new category name",
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Returns updated category resource",
     *              @OA\JsonContent(
     *                  ref="#/components/schemas/CategoryResource",
     *                  @OA\Examples(
     *                      ref="#/components/schemas/CategoryResource",
     *                  ),
     *              ),
     *     ),
     *     @OA\Response(response="404", description="Category not found", @OA\JsonContent())
     * )
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->name = $request->input('name');
        $category->save();

        return new CategoryResource($category);
    }
}
