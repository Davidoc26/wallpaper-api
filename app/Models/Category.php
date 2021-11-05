<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, Sluggable;

    public const PAGINATION_LIMIT = 15;

    protected $fillable = ['name'];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => true,
                'onUpdate' => false, // specify true if slug should change during update
            ],
        ];
    }
}
