<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['name', 'icon'];

    public function contentRating()
    {
        return $this->hasMany(ContentRating::class, 'rating_id');
    }

}
