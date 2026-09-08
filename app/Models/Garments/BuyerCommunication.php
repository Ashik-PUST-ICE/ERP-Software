<?php
namespace App\Models\Garments;
use Illuminate\Database\Eloquent\Model;
class BuyerCommunication extends Model
{
    protected $table = 'garment_buyer_communications';
    protected $fillable = ['order_id','user_id','channel','communicated_at','subject','notes'];
    protected $casts = ['communicated_at' => 'datetime'];
    public function order() { return $this->belongsTo(GarmentOrder::class); }
}
