<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['vendor_id', 'name', 'description', 'price', 'stock_quantity', 'image_url'];

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

}
