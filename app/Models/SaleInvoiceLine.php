<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleInvoiceLine extends Model
{
    protected $fillable = ['sale_invoice_id', 'product_id', 'price', 'quantity', 'total'];

    public function saleInvoice()
    {
        return $this->belongsTo(SaleInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
