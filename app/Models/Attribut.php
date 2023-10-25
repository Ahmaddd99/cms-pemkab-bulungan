<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribut extends Model
{
    use HasFactory;
    protected $guarded = 'id';
    protected $fillable = ['name', 'slug'];

    public function attributesValue()
    {
        return $this->hasMany(AttributesValue::class, 'attribut_id', 'id');
    }
}
