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
use function response;

final class ImageController extends Controller
{
    public function store(ImageUploadRequest $request, ImageService $imageService): ImageResource
    {
        $image = $imageService->upload($request->getDto());

        return new ImageResource($image);
    }

    public function storeAll(ImagesUploadRequest $request, ImageService $imageService): JsonResponse
    {
        $imageService->uploadAll($request->getDto());

        return response()->json(['status' => 'ok']);
    }

    public function addCategory(AddCategoryToImageRequest $request, Image $image, ImageService $imageService): ImageResource
    {
        $image = $imageService->attachCategory($request->input('category_id'), $image);

        return new ImageResource($image);
    }

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
