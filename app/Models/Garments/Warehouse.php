<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'garment_warehouses';
    protected $fillable = ['code', 'name', 'address', 'status'];
}
