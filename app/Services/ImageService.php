<?php

namespace App\Services;

use App\Dto\Collections\ImageUploadCollection;
use App\Dto\Collections\SavedImageCollection;
use App\Dto\DownloadImageDto;
use App\Dto\ImageUploadDto;
use App\Dto\SavedImageDto;
use App\Exceptions\ImageDownloadException;
use App\Models\Category;
use App\Models\Image;
use App\Services\Uploader\ImageUploader;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function collect;
use function count;

final class ImageService
{
    private ImageUploader $imageUploader;
    private ImageManager $imageManager;
    private Filesystem $filesystem;
    private ValidatorInterface $validator;

    public function __construct(ImageUploader $imageUploader, Imagemanager $imageManager, Filesystem $filesystem, ValidatorInterface $validator)
    {
        $this->imageUploader = $imageUploader;
        $this->imageManager = $imageManager;
        $this->filesystem = $filesystem;
        $this->validator = $validator;
    }

    /**
     * @param ImageUploadDto $imageUploadDto
     * @return Image
     */
    public function upload(ImageUploadDto $imageUploadDto): Image
    {
        $savedImage = $this->imageUploader->upload($imageUploadDto);
        return $this->store($savedImage);
    }

    /**
     * @param ImageUploadCollection $imageUploadDtoCollection
     * @return Collection
     */
    public function uploadAll(ImageUploadCollection $imageUploadDtoCollection): Collection
    {
        $savedImages = $this->imageUploader->uploadAll($imageUploadDtoCollection);
        return $this->storeAll($savedImages);
    }

    /**
     * @param SavedImageDto $dto
     * @return Image
     */
    public function store(SavedImageDto $dto): Image
    {
        if (null !== $dto->getCategoryId()) {
            if (!Category::where('id', $id = $dto->getCategoryId())->exists()) {
                $this->imageUploader->removeSavedImage($dto);
                throw new ModelNotFoundException("Category with id $id not found");
            }
            return $this->storeWithCategory($dto);
        }

        return $this->storeImage($dto);
    }

    /**
     * @param SavedImageDto $dto
     * @return Image
     */
    private function storeWithCategory(SavedImageDto $dto): Image
    {
        $image = $this->storeImage($dto);
        $image->categories()->attach($dto->getCategoryId());

        return $image;
    }

    /**
     * @param SavedImageDto $dto
     * @return Image
     */
    private function storeImage(SavedImageDto $dto): Image
    {
        $image = new Image();
        $image->user_id = $dto->getUserId();
        $image->name = $dto->getName();
        $image->path = $dto->getPath();
        $image->save();

        return $image;
    }

    /**
     * @param SavedImageCollection $savedImages
     * @return Collection
     */
    public function storeAll(SavedImageCollection $savedImages): Collection
    {
        $images = collect();

        foreach ($savedImages as $savedImage) {
            $image = $this->store($savedImage);
            $images->add($image);
        }

        return $images;
    }

    /**
     * @param int $id
     * @param Image $image
     * @return Image
     */
    public function attachCategory(int $id, Image $image): Image
    {
        if (!Category::where('id', $id)->exists()) {
            throw new ModelNotFoundException("Category with id $id not found");
        }

        $image->categories()->attach($id);
        $image->load('categories');

        return $image;
    }

    public function download(DownloadImageDto $dto): \Intervention\Image\Image
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ImageDownloadException($violations);
        }

        if ($dto->getHeight() && $dto->getWidth()) {
            return $this->resize($dto->getPath(), $dto->getWidth(), $dto->getHeight());
        }

        return $this->imageManager->make($this->filesystem->path($dto->getPath()));
    }

    private function resize(string $path, int $width, int $height): \Intervention\Image\Image
    {
        $img = $this->imageManager->make($this->filesystem->path($path));
        return $img->resize($width, $height);
    }
}
