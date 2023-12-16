<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'url',
        'user_id',
    ];

    //Relacionamento com o usuário que fez o upload
    public function user() {
        return $this->belongsTo(User::class);
    }
}
