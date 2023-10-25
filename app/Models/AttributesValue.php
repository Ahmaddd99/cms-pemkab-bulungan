<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributesValue extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['content_id', 'attribut_id', 'description', 'order'];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }

    public function attribut()
    {
        return $this->belongsTo(Attribut::class, 'attribut_id', 'id');
    }
}
