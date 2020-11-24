<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uuid',
        'uuid_admin',
        'title',
        'video_platform',
        'youtube_video_id'
    ];

    protected $appends = [
        'video_platform'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'uuid',
        'uuid_admin',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->uuid = \Str::uuid();
            $model->uuid_admin = \Str::uuid();
        });
    }

    public function getRouteKeyName() {
        return 'uuid';
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getVideoPlatformAttribute()
    {
        $video_platform = '';
        if (!empty($this->youtube_video_id)) {
            $video_platform = 'youtube';
        }
        return $video_platform;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
