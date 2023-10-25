<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable =['category_id', 'name', 'image', 'published'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function content()
    {
        return $this->hasMany(Content::class, 'subcategory_id', 'id');
    }
}
