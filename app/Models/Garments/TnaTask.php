<?php

namespace App\Models\Garments;

use App\Models\HRM\HrmEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TnaTask extends Model
{
    use HasFactory;

    protected $table = 'garment_tna_tasks';

    protected $fillable = [
        'order_id', 'employee_id', 'task_name', 'task_type',
        'planned_date', 'actual_date', 'status', 'notes',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'actual_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrmEmployee::class, 'employee_id');
    }
}
