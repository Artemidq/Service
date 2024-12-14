<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = ["message", "user_id", "type_id", "status_id"];
    protected $hidden = [];

    public function status(){
        return $this->belongsTo(StatusService::class);
    }

    public function type(){
        return $this->belongsTo(TypeService::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsToThrough(
            CategoryService::class,
            TypeService::class,
            foreignKeyLookup: [
                CategoryService::class => 'category_id',
                TypeService::class => 'type_id'
            ],
            localKeyLookup: [
                Appeal::class => 'id',
                TypeService::class => 'id'
            ]);
    }
}
