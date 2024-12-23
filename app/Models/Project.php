<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'html',
        'css',
        'javascript',
        'uuid',
        'user_id',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->uuid)) {
                $project->uuid = Str::uuid()->toString();
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

