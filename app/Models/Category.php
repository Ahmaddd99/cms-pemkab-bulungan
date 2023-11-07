<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['name', 'image', 'image_placeholder', 'published'];

    public function subcategory()
    {
        return $this->hasMany(Subcategory::class, 'category_id', 'id');
    }

    public function content()
    {
        return $this->hasMany(Content::class, 'category_id', 'id');
    }
}
