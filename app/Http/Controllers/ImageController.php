<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCategoryToImageRequest;
use App\Http\Requests\ImagesUploadRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Category;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use function response;

final class ImageController extends Controller
{
    public function store(ImageUploadRequest $request, ImageService $imageService): JsonResponse
    {
        $imageService->upload($request->getDto());

        return response()->json(['status' => 'ok']);
    }

    public function storeAll(ImagesUploadRequest $request, ImageService $imageService): JsonResponse
    {
        $imageService->uploadAll($request->getDto());

        return response()->json(['status' => 'ok']);
    }

    public function addCategory(AddCategoryToImageRequest $request, Image $image)
    {
        if (!Category::where('id', $id = $request->input('category_id'))->exists()) {
            throw new ModelNotFoundException("Category with id $id not found");
        }

        $image->categories()->attach($request->input('category_id'));
        $image->load('categories');

        dd($image);
    }
}
