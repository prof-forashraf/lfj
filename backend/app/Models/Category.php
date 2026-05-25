<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'parent_id',
        'seo_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'schema_type',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Relationship for parent category (for subcategories)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship for child categories
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Relationship with Posts (many-to-many)
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post'); // Assumes pivot table 'category_post'
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    protected function effectiveSeoTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->seo_title ?: $this->name, 60, '')
        );
    }

    protected function effectiveMetaDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->meta_description ?: strip_tags($this->description ?? ''), 160, '')
        );
    }

    protected function effectiveOgImage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->og_image
                ? (Str::startsWith($this->og_image, ['http://', 'https://']) ? $this->og_image : Storage::disk('public')->url($this->og_image))
                : ($this->image ? Storage::disk('public')->url($this->image) : url('/images/category-placeholder.png'))
        );
    }
}
