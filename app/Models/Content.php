<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['category_id', 'subcategory_id', 'image', 'title', 'body', 'meta'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }

    public function attributValue()
    {
        return $this->hasMany(AttributesValue::class, 'content_id', 'id');
    }

    public function featureValue()
    {
        return $this->hasOne(FeatureValue::class, 'content_id', 'id');
    }
}
