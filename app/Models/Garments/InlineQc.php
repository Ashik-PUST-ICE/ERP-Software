<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InlineQc extends Model
{
    use HasFactory;
    protected $table = 'garment_inline_qcs';
    protected $fillable = ['order_id','inspection_point','inspection_date','checked_quantity','passed_quantity','defect_quantity','inspector_name','status','notes'];
    protected $casts = ['inspection_date'=>'date','checked_quantity'=>'integer','passed_quantity'=>'integer','defect_quantity'=>'integer'];
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
}
