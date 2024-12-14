<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryService extends Model
{
    protected $fillable = [
        'name'
    ];

    public function types() {
        return $this->hasManyThrough(
            Appeal::class, 
            TypeService::class, 
            'category_id', 
            'type_id', 
            'id', 
            'id'
        );
    }
}
