<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['user_id', 'category_id', 'content', 'book', 'chapter', 'verses', 'is_public'];
}