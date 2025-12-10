<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'file_name', 'file_path', 'disk', 'mime_type',
        'size', 'width', 'height', 'alt_text', 'caption',
        'type', 'user_id', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
