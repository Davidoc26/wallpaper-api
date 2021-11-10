<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class ImageUploadDto
{
    #[Assert\NotBlank]
    private int $userId;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\File]
    private UploadedFile $uploadedFile;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Positive]
    private ?int $categoryId;

    /**
     * @param int $userId
     * @param string $name
     * @param UploadedFile $uploadedFile
     * @param int|null $categoryId
     */
    public function __construct(int $userId, string $name, UploadedFile $uploadedFile, ?int $categoryId = null)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->uploadedFile = $uploadedFile;
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @return ?int
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }
}
