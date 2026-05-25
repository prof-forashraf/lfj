<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property string $amazon_asin
 * @property array|null $image_urls
 * @property string|null $local_image_path
 * ... etc ...
 */
class AffiliateProduct extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'amazon_asin',
        'product_name_snapshot',
        'amazon_url',
        'price',
        'image_urls',
        'your_notes',
        'main_image_url_snapshot',
        'try_on_image_url',
        'item_type',
        'is_featured',
        'status',
        'category_id',
        'local_image_path',
        'try_on_type',
        'try_on_anchor',
        'try_on_scale_default',
        'try_on_offset_y',
        'seo_title',
        'meta_description',
        'og_title',
        'og_description',
        'schema_type',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'is_featured' => 'boolean',
        'price' => 'float',
        'try_on_scale_default' => 'float',
        'try_on_offset_y' => 'integer',
    ];

    // Relationship to Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship to Posts (many-to-many)
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'affiliate_product_post');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->local_image_path
            ? Storage::disk('public')->url($this->local_image_path)
            : url('/images/placeholder.png')
        );
    }

    protected function effectiveSeoTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->seo_title ?: $this->product_name_snapshot, 60, '')
        );
    }

    protected function effectiveMetaDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->meta_description ?: strip_tags($this->your_notes ?: $this->product_name_snapshot), 160, '')
        );
    }

    protected function effectiveOgImage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image_url ?: $this->main_image_url_snapshot ?: url('/images/product-placeholder.png')
        );
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'product_name_snapshot' => $this->product_name_snapshot,
            'your_notes' => $this->your_notes,
            'amazon_asin' => $this->amazon_asin,
            'price' => $this->price,
            'category' => $this->category?->name,
            'category_slug' => $this->category?->slug,
            'is_featured' => $this->is_featured,
            'item_type' => $this->item_type,
            'seo_title' => $this->seo_title,
            'meta_description' => $this->meta_description,
        ];
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'affiliate_products';
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status === 'active';
    }
}
