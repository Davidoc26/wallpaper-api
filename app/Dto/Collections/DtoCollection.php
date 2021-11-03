<?php

namespace App\Dto\Collections;

use IteratorAggregate;

abstract class DtoCollection implements IteratorAggregate
{
    public function __construct()
    {
        return $this;
    }
}
