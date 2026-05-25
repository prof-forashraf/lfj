<?php
//app/Models/JewelryMaterial.php
namespace App\Models;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon; // Make sure Carbon is imported

/**
 * App\Models\JewelryMaterial
 *
 * IMPORTANT: Cost Basis, Not Final Price
 *
 * This model stores cost basis for jewelry materials, NOT customer-facing prices.
 *
 * Formula for final selling price:
 *   final_price = cost_basis + fabrication + labor + wastage + findings
 *                 + gemstone_cost + certification + margin + tax
 *
 * Where:
 *   - cost_basis = price_per_unit (from this model)
 *   - fabrication = custom design costs
 *   - labor = craftsperson hours
 *   - wastage = metal loss percentage
 *   - findings = clasps, posts, etc.
 *   - gemstone_cost = diamond/gemstone cost
 *   - certification = appraisal/grading fees
 *   - margin = business profit margin
 *   - tax = sales/VAT
 *
 * @property int $id
 * @property string $material_type
 * @property string $material_name
 * @property float $price_per_unit (COST BASIS)
 * @property string $currency_code
 * @property bool $is_active
 * @property bool $is_auto_synced (auto-synced from market API)
 * @property string|null $sync_source (e.g., "metal_api_gold", "supplier_feed")
 * @property Carbon|null $last_synced_at
 * @property bool $manual_override (temporary price override)
 * @property decimal $markup_percentage (internal markup for this material)
 * @property int|null $updated_by (user who last changed price)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
enum MaterialType: string
{
    case Metal = 'Metal';
    case Gemstone = 'Gemstone';
    case Finding = 'Finding';
    case Labor = 'Labor';
}
enum UnitType: string
{
    case Gram = 'gram';
    case Carat = 'carat';
    case Piece = 'piece';
    case Hour = 'hour';
}
class JewelryMaterial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jewelry_materials';

    protected $attributes = [
        'is_auto_synced' => false,
        'manual_override' => false,
        'markup_percentage' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'material_type',
        'material_name',
        'material_category',
        'unit_type',
        'price_per_unit',
        'currency_code',
        'purity_grade',
        'quality_grade',
        'supplier',
        'minimum_order_quantity',
        'date_updated',
        'price_valid_until',
        'market_region',
        'is_active',
        'markup_percentage',
        'is_auto_synced',
        'sync_source',
        'last_synced_at',
        'manual_override',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'material_type' => MaterialType::class, // Use the Enum
        'unit_type' => UnitType::class,       // Use the Enum
        'date_updated' => 'date',
        'price_valid_until' => 'date',
        'is_active' => 'boolean',
        'price_per_unit' => 'float',
        'markup_percentage' => 'decimal:2',
        'is_auto_synced' => 'boolean',
        'manual_override' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted(): void
    {
        static::created(function (self $material) {
            $material->recordAudit('created');
        });

        static::updated(function (self $material) {
            $material->recordAudit('updated');
        });

        static::deleted(function (self $material) {
            $material->recordAudit('deleted');
        });
    }

    protected function recordAudit(string $action): void
    {
        if (! Auth::check()) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => self::class,
            'model_id' => $this->id,
            'action' => $action,
            'old_values' => $this->getOriginal(),
            'new_values' => $this->getChanges(),
        ]);
    }
}
