<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','img_url', 'price', 'unit', 'category_id', 'user_id'];
}
