<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefectRejection extends Model
{
    use HasFactory;
    protected $table = 'garment_defect_rejections';
    protected $fillable = ['order_id','inline_qc_id','defect_type','section','defect_quantity','rejected_quantity','root_cause','corrective_action','status','reported_date','notes'];
    protected $casts = ['reported_date'=>'date','defect_quantity'=>'integer','rejected_quantity'=>'integer'];
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
    public function inlineQc(): BelongsTo { return $this->belongsTo(InlineQc::class, 'inline_qc_id'); }
}
