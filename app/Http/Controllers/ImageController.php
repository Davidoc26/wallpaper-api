<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagesUploadRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Services\ImageService;
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
}
