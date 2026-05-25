<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property Carbon $start_datetime
 * @property Carbon|null $end_datetime
 * @property string|null $location_name
 * @property string|null $location_address
 * @property string|null $event_url
 * @property string|null $featured_image_url_snapshot
 * @property array|null $image_urls
 * @property string|null $featured_image
 * @property string $status
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $image_url
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_datetime',
        'end_datetime',
        'location_name',
        'location_address',
        'event_url',
        'status',
        'seo_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'schema_type',
        'featured_image_url_snapshot',
        'image_urls',   // JSON of all resized images
        'featured_image', // main/original image path
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'image_urls' => 'array',
    ];

    /**
     * Accessor: Returns the public URL for the featured image (large size),
     * or a fallback placeholder if none exists.
     * Images are stored in image_urls JSON as ['small', 'medium', 'large'] => paths
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() =>
            is_array($this->image_urls) && isset($this->image_urls['large'])
            ? Storage::disk('public')->url($this->image_urls['large'])
            : ($this->featured_image_url_snapshot
                ? $this->featured_image_url_snapshot
                : url('/images/placeholder.png')
            )
        );
    }

    /**
     * Boot logic:
     * - Auto-generate slug
     * - Auto-update slug when title changes
     * - Auto-delete images when an event is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = static::createUniqueSlug($event->title);
            }
        });

        static::updating(function ($event) {
            if (
                $event->isDirty('title') &&
                (empty($event->slug) || $event->slug === Str::slug($event->getOriginal('title')))
            ) {
                $event->slug = static::createUniqueSlug($event->title, $event->id);
            }
        });

        // ✅ Delete images when an event is deleted
        // image_urls is a JSON array of paths to all image sizes
        static::deleting(function (Event $event) {
            if (is_array($event->image_urls)) {
                foreach ($event->image_urls as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        });
    }

    /**
     * Generate a unique slug for the event.
     */
    protected static function createUniqueSlug(string $title, int $idToIgnore = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = static::where('slug', $slug);
        if ($idToIgnore) {
            $query->where('id', '!=', $idToIgnore);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', $slug);
            if ($idToIgnore) {
                $query->where('id', '!=', $idToIgnore);
            }
        }

        return $slug;
    }

    protected function effectiveSeoTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->seo_title ?: $this->title, 60, '')
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
                : $this->image_url
        );
    }
}
