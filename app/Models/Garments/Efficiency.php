<?php

namespace App\Models\Garments;

use App\Models\HRM\HrmEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Efficiency extends Model
{
    use HasFactory;

    protected $table = 'garment_efficiencies';

    protected $fillable = [
        'employee_id', 'order_id', 'machine_name', 'line_name', 'work_date',
        'working_minutes', 'target_output', 'actual_output', 'efficiency_percentage', 'status', 'notes',
    ];

    protected $casts = [
        'work_date' => 'date', 'working_minutes' => 'integer', 'target_output' => 'integer',
        'actual_output' => 'integer', 'efficiency_percentage' => 'decimal:4',
    ];

    public function employee(): BelongsTo { return $this->belongsTo(HrmEmployee::class, 'employee_id'); }
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
}
