<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class DownloadImageDto
{
    #[Assert\NotBlank]
    private string $id;

    #[Assert\NotBlank]
    private string $path;

    #[Assert\LessThanOrEqual(10000)]
    #[Assert\GreaterThanOrEqual(10)]
    private ?int $width;

    #[Assert\LessThanOrEqual(10000)]
    #[Assert\GreaterThanOrEqual(10)]
    private ?int $height;

    public function __construct(string $id, string $path, ?int $width, ?int $height)
    {
        $this->id = $id;
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

}
