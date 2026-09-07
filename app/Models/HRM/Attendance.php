<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'hrm_attendances';

    protected $fillable = ['employee_id', 'date', 'check_in', 'check_out', 'status', 'notes'];

    protected $casts = ['date' => 'date'];

    public function employee()
    {
        return $this->belongsTo(HrmEmployee::class, 'employee_id');
    }

    public function getWorkingHoursAttribute(): ?string
    {
        if ($this->check_in && $this->check_out) {
            $in = \Carbon\Carbon::parse($this->check_in);
            $out = \Carbon\Carbon::parse($this->check_out);
            $diff = $in->diff($out);
            return $diff->h . 'h ' . $diff->i . 'm';
        }
        return null;
    }
}
