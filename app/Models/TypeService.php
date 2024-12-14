<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    protected $fillable = ["name", "category_id"];

    public function category() {
        return $this->belongsTo(CategoryService::class, 'category_id', 'id');
    }

    public function appeals() {
        return $this->hasMany(Appeal::class, 'type_id');
    }

}
