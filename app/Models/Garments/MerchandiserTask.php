<?php
namespace App\Models\Garments;
use Illuminate\Database\Eloquent\Model;
class MerchandiserTask extends Model
{
    protected $table = 'garment_merchandiser_tasks';
    protected $fillable = ['order_id','user_id','title','due_date','priority','status','notes'];
    protected $casts = ['due_date' => 'date'];
    public function order() { return $this->belongsTo(GarmentOrder::class); }
}
