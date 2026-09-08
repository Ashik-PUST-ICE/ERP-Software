<?php
namespace App\Models\Garments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Incentive extends Model
{
    use HasFactory;
    protected $table = 'garment_incentives';
    protected $fillable = ['order_id','employee_name','production_date','operation','production_quantity','piece_rate','incentive_amount','status','notes'];
    protected $casts = ['production_date'=>'date','production_quantity'=>'integer','piece_rate'=>'decimal:4','incentive_amount'=>'decimal:4'];
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
}
