<?php

namespace App\Models;

use App\Models\SaleInvoiceLine;
use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    protected $fillable = ['customer_name', 'subtotal', 'vat', 'total', 'user_id', 'is_draft'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(SaleInvoiceLine::class);
    }
}
