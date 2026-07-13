<?php

namespace App\Models;

use App\Models\PurchaseInvoiceLine;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = ['supplier_id', 'subtotal', 'vat', 'total', 'user_id', 'is_draft'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseInvoiceLine::class);
    }
}
