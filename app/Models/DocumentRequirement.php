<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequirement extends Model
{
    protected $fillable = [
    'label',
    'input_name',
    'category_id',
    'type_id',
    'section',
    'is_required',
];

 public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}

