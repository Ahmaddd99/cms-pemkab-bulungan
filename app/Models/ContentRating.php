<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentRating extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['content_id', 'rating_id'];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'id', 'rating_id');
    }


}
