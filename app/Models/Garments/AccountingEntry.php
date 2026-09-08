<?php
namespace App\Models\Garments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AccountingEntry extends Model
{
    use HasFactory;
    protected $table = 'garment_accounting_entries';
    protected $fillable = ['order_id','entry_type','account_code','account_name','entry_date','debit','credit','reference','status','description'];
    protected $casts = ['entry_date'=>'date','debit'=>'decimal:4','credit'=>'decimal:4'];
    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
}
