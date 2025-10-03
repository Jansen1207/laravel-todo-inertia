<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'notes', 'is_done'];
    protected $casts = ['is_done' => 'boolean'];
}
