<?php

namespace App\Http\Controllers;

use App\Dto\DownloadImageDto;
use App\Http\Requests\AddCategoryToImageRequest;
use App\Http\Requests\DownloadImageRequest;
use App\Http\Requests\ImagesUploadRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use function response;

final class ImageController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/images",
     *     summary="Create an image",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary",
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      format="string",
     *                  ),
     *              ),
     *          ),
     *     ),
     * @OA\Response(response="422",description="Validation failed", @OA\JsonContent()),
     * @OA\Response(response="201",description="Image was created",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/ImageResource",
     *              @OA\Examples(
     *                  ref="#/components/schemas/ImageResource"
     *              ),
     *          ),
     *      ),
     * )
     */
    public function store(ImageUploadRequest $request, ImageService $imageService): ImageResource
    {
        $image = $imageService->upload($request->getDto());

        return new ImageResource($image);
    }

    /**
     * @OA\Post(
     *     path="/api/images/upload-all",
     *     summary="Upload multiple images",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              encoding={
     *                  "images[][name]": {
     *                      "explode": true,
     *                  },
     *              },
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="images[][name]",
     *                      type="array",
     *                      @OA\Items(
     *                          propertyNames="images",
     *                          type="string",
     *                          format="array",
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="images[][image]",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  ),
     *              ),
     *          ),
     *     ),
     * @OA\Response(response="422",description="Validation failed", @OA\JsonContent()),
     * @OA\Response(response="200",description="Images were uploaded", @OA\JsonContent())
     * )
     */
    public function storeAll(ImagesUploadRequest $request, ImageService $imageService): JsonResponse
    {
        $imageService->uploadAll($request->getDto());

        return response()->json(['status' => 'ok']);
    }

    /**
     * @OA\Post(
     *      path="/api/images/{imageId}/add-category",
     *      summary="Add category to an image",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="category_id",
     *                      description="Category id",
     *                      type="integer",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="imageId",
     *          in="path",
     *          description="ID of an image",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/ImageResource",
     *              @OA\Examples(ref="#/components/schemas/ImageResource"),
     *          ),
     *      ),
     * @OA\Response(response="404",description="Image/Category not found",@OA\JsonContent()),
     * @OA\Response(response="422",description="Validation failed",@OA\JsonContent()),
     * )
     */
    public function addCategory(AddCategoryToImageRequest $request, Image $image, ImageService $imageService): ImageResource
    {
        $image = $imageService->attachCategory($request->input('category_id'), $image);

        return new ImageResource($image);
    }

    /**
     * @OA\Get(
     *     path="/api/images/{imageId}/download",
     *     @OA\Parameter(
     *          in="path",
     *          name="imageId",
     *          required=true,
     *          example="1",
     *      ),
     *     @OA\Parameter(
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="width",
     *                  type="integer",
     *                  example="1920",
     *                  maximum="10000",
     *                  minimum="10",
     *              ),
     *          ),
     *          in="query",
     *          name="width",
     *     ),
     *     @OA\Parameter (
     *          @OA\Schema (
     *              @OA\Property(
     *                  property="height",
     *                  type="integer",
     *                  example="1080",
     *                  maximum="10000",
     *                  minimum="10",
     *              )
     *          ),
     *          in="query",
     *          name="height",
     *     ),
     *     @OA\Response(description="Image response",response="200",
     *          @OA\MediaType(
     *              mediaType="image/png",
     *          ),
     *      ),
     *     @OA\Response(description="Validation failed", response="422", @OA\JsonContent())
     * )
     */
    public function download(DownloadImageRequest $request, Image $image, ImageService $imageService): mixed
    {
        $img = $imageService->download(new DownloadImageDto(
            $image->id,
            $image->path,
            $request->input('width'),
            $request->input('height')
        ));

        return $img->response(quality: 100);
    }
}
