<?php

declare(strict_types=1);

namespace App\Services\Uploader;

use App\Dto\Collections\ImageUploadCollection;
use App\Dto\Collections\SavedImageCollection;
use App\Dto\ImageUploadDto;
use App\Dto\SavedImageDto;
use App\Exceptions\ImageUploadException;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;
use function now;

final class ImageUploader
{
    private Filesystem $filesystem;
    private ValidatorInterface $validator;
    private Repository $config;

    /**
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @param Repository $config
     */
    public function __construct(Filesystem $filesystem, ValidatorInterface $validator, Repository $config)
    {
        $this->filesystem = $filesystem;
        $this->validator = $validator;
        $this->config = $config;
    }

    /**
     * @param ImageUploadDto $dto
     * @return SavedImageDto
     */
    public function upload(ImageUploadDto $dto): SavedImageDto
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ImageUploadException();
        }

        $path = $this->generatePath();

        $savedPath = $this->filesystem->putFile($path, $dto->getUploadedFile());

        return $this->prepareDto($dto->getUserId(), $dto->getName(), $savedPath);
    }

    /**
     * @param ImageUploadCollection $images
     * @return SavedImageCollection
     */
    public function uploadAll(ImageUploadCollection $images): SavedImageCollection
    {
        $collection = new SavedImageCollection();

        foreach ($images as $image) {
            $collection->add($this->upload($image));
        }

        return $collection;
    }

    /**
     * @return string
     */
    private function generatePath(): string
    {
        return $this->config->get('image.wallpapers-path') . now()->format('d_m_y');
    }

    /**
     * @param int $userId
     * @param string $name
     * @param string $path
     * @return SavedImageDto
     */
    private function prepareDto(int $userId, string $name, string $path): SavedImageDto
    {
        return new SavedImageDto($userId, $name, $path);
    }
}
