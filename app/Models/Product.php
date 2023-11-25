<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description','price', 'compare_price', 'status', 'category_id', 'sub_category_id',	'brand_id', 'sku','barcode','track_qty','qty'];

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }

    public function product_ratings(){
        return $this->hasMany(ProductRating::class)->where('status', 1);
    }
}
