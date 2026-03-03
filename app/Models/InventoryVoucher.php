<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class InventoryVoucher extends Model
{
    use Auditable;
    const TYPE_INBOUND = 'INBOUND';

    const TYPE_OUTBOUND = 'OUTBOUND';

    const STATUS_PENDING = 'PENDING';

    const STATUS_COMPLETED = 'COMPLETED';

    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'voucher_code',
        'type',
        'warehouse_id',
        'supplier_id',
        'user_id',
        'voucher_date',
        'status',
        'total_amount',
        'note',
    ];

    protected $casts = [
        'voucher_date' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(InventoryVoucherDetail::class);
    }
}
