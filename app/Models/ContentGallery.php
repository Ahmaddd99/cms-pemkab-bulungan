<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentGallery extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['content_id', 'image'];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }
}
