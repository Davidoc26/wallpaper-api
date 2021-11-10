<?php

namespace App\Dto;

final class SavedImageDto
{
    private int $userId;
    private string $name;
    private string $path;
    private ?int $categoryId;

    public function __construct(int $userId, string $name, string $path, ?int $categoryId = null)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->path = $path;
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
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return ?int
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

}
