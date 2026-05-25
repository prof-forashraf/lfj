<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'seo_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'schema_type',
    ];

    // Relationship with Posts (many-to-many)
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag'); // Assumes pivot table 'post_tag'
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
            // Add unique slug generation if needed
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
            get: fn() => Str::limit($this->meta_description ?: 'Articles tagged with ' . $this->name, 160, '')
        );
    }

    protected function effectiveOgTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->og_title ?: $this->effectiveSeoTitle
        );
    }

    protected function effectiveOgDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->og_description ?: $this->effectiveMetaDescription
        );
    }

    protected function effectiveSchemaType(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->schema_type ?: 'CollectionPage'
        );
    }
}
