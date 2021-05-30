<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    /**
     * Get the comments for the blog post.
     */
    public function productcategory()
    {
        return $this->hasMany('App\Models\ProductCategory', 'pro_id');
    }
}
