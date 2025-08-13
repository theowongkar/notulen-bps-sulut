<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    /** @use HasFactory<\Database\Factories\MinuteFactory> */
    use HasFactory, Sluggable;

    protected $fillable = [
        'followed_up_by',
        'problem',
        'slug',
        'solution',
        'follow_up_plan',
        'follow_up_limits',
        'data_source',
        'evidence'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'problem'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'followed_up_by');
    }
}
