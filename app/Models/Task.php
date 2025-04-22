<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description_en',
        'description_ar',
        'section',
        'language',
        'is_completed',
        'user_id',
        'is_daily',
        'is_sensitive',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(TaskImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
