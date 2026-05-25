<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'youtube_video_id',
        'description',
        'duration_seconds',
        'status',
        'is_featured',
        'post_id',
        'seo_title',
        'meta_description',
        'og_title',
        'og_description',
        'schema_type',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'duration_seconds' => 'integer',
    ];

    /**
     * Accessor to get the YouTube thumbnail URL.
     * YouTube provides several thumbnail sizes.
     * - default.jpg (120x90)
     * - mqdefault.jpg (320x180) -> medium quality
     * - hqdefault.jpg (480x360) -> high quality
     * - sddefault.jpg (640x480) -> standard definition
     * - maxresdefault.jpg (1920x1080) -> max resolution (may not always exist)
     */
    public function getYoutubeThumbnailUrlAttribute(): string
    {
        // Using hqdefault as a good balance of quality and availability
        return "https://img.youtube.com/vi/{$this->youtube_video_id}/hqdefault.jpg";
    }

    /**
     * Accessor to get the YouTube embed URL.
     */
    public function getYoutubeEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube_video_id}";
    }

    // Optional: Relationship to Post if a video is tied to a blog post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
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
            get: fn() => $this->youtube_thumbnail_url
        );
    }
}
