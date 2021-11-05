<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Image extends Model
{
    use HasFactory;

    public const PAGINATION_LIMIT = 20;

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
