<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalInspection extends Model
{
    use HasFactory;
    protected $table = 'garment_final_inspections';
    protected $fillable = ['order_id','inspection_lot','inspection_date','lot_quantity','sample_quantity','aql_level','defect_quantity','rejected_quantity','result','inspector_name','notes'];
    protected $casts = ['inspection_date'=>'date','lot_quantity'=>'integer','sample_quantity'=>'integer','defect_quantity'=>'integer','rejected_quantity'=>'integer'];
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
}
