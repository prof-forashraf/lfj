<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Observers\SeoObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $excerpt
 * @property string|null $featured_image_url_snapshot
 * @property array|null $image_urls
 * @property string|null $featured_image
 * @property string $status
 * @property Carbon|null $published_at
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property array|null $meta_keywords
 * @property string|null $canonical_url
 * @property bool $noindex
 * @property bool $nofollow
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $author
 * @property-read string|null $image_url
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content', // <-- THE CRITICAL FIX IS ADDING THIS LINE
        'excerpt',
        'status',
        'published_at',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'noindex',
        'nofollow',
        'featured_image_url_snapshot',
        'image_urls',
        'featured_image',
        'is_featured',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'schema_type',
        'focus_keyword',
        'reading_time',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'published_at' => 'datetime',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
        'meta_keywords' => 'array',
        'image_urls' => 'array',
        'is_featured' => 'boolean'
    ];

    /**
     * Accessor for a full, ready-to-use image URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->featured_image
            ? Storage::disk('public')->url($this->featured_image)
            : url('/images/placeholder.png')
        );
    }

    /**
     * Get the author of the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Boot method to handle model events.
     * Automatically generate slug from title if slug is empty.
     */
    protected static function boot()
    {
        parent::boot();

        static::observe(SeoObserver::class);

        static::creating(function ($post) {
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = static::createUniqueSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && (empty($post->slug) || $post->slug === Str::slug($post->getOriginal('title')))) {
                $post->slug = static::createUniqueSlug($post->title, $post->id);
            }
        });
    }

    protected static function createUniqueSlug(string $title, int $postIdToIgnore = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        $query = static::where('slug', $slug);
        if ($postIdToIgnore) {
            $query->where('id', '!=', $postIdToIgnore);
        }
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', $slug);
            if ($postIdToIgnore) {
                $query->where('id', '!=', $postIdToIgnore);
            }
        }
        return $slug;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function affiliateProducts(): BelongsToMany
    {
        return $this->belongsToMany(AffiliateProduct::class, 'affiliate_product_post');
    }

    /**
     * SEO Accessors
     */
    protected function effectiveSeoTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->seo_title ?: $this->title, 60, '')
        );
    }

    protected function effectiveMetaDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->meta_description ?: strip_tags($this->excerpt ?: $this->content), 160, '')
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

    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $rawValue = $this->getAttributes()['reading_time'] ?? null;
                return $rawValue ?? ceil(Str::wordCount(strip_tags($this->content ?? '')) / 200);
            }
        );
    }
}
