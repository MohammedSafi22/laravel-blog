<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // we have a post and it belong to user
    public function user(){
        return $this->belongsTo(User::class);
    }
    // we have a post and it belong to category
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
