<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class MerchandiserHandover extends Model
{
    protected $table = 'garment_merchandiser_handovers';
    protected $fillable = ['order_id', 'from_user_id', 'to_user_id', 'notes', 'handed_over_at'];
    protected $casts = ['handed_over_at' => 'datetime'];

    public function order() { return $this->belongsTo(GarmentOrder::class); }
    public function fromUser() { return $this->belongsTo(\App\Models\User::class, 'from_user_id'); }
    public function toUser() { return $this->belongsTo(\App\Models\User::class, 'to_user_id'); }
}
