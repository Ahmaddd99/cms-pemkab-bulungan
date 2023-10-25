<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['title', 'image', 'published', 'order'];
    
    public function featureValue()
    {
        return $this->hasMany(FeatureValue::class, 'feature_id', 'id');
    }
}
