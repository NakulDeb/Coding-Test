<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function product_variants(){
        return $this->hasMany(ProductVariant::class);
        
    }
}
