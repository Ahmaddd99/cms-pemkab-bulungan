<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureValue extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['feature_id', 'content_id'];

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id', 'id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }
}
