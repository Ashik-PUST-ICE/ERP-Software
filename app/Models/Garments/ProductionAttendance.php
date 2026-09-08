<?php
namespace App\Models\Garments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\HRM\HrmEmployee;
class ProductionAttendance extends Model
{
    use HasFactory;
    protected $table = 'garment_production_attendances';
    protected $fillable = ['employee_id','order_id','line_name','attendance_date','status','production_quantity','working_hours','notes'];
    protected $casts = ['attendance_date'=>'date','production_quantity'=>'integer','working_hours'=>'decimal:2'];
    public function employee(): BelongsTo { return $this->belongsTo(HrmEmployee::class, 'employee_id'); }
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
}
