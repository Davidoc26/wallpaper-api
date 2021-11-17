<?php

namespace App\Dto\Collections;

use App\Dto\ImageUploadDto;
use ArrayIterator;
use IteratorAggregate;

final class ImageUploadCollection extends DtoCollection
{
    /**
     * @var array<ImageUploadDto>
     */
    private array $images;

    /**
     * @param ImageUploadDto $imageUploadDto
     */
    public function add(ImageUploadDto $imageUploadDto): void
    {
        $this->images[] = $imageUploadDto;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->images);
    }
}
