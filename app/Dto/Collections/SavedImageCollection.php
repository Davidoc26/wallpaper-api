<?php

namespace App\Dto\Collections;

use App\Dto\SavedImageDto;
use ArrayIterator;

final class SavedImageCollection extends DtoCollection
{
    /**
     * @var array<SavedImageDto>
     */
    private array $savedImages;

    /**
     * @param SavedImageDto $dto
     */
    public function add(SavedImageDto $dto): void
    {
        $this->savedImages[] = $dto;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->savedImages);
    }
}
