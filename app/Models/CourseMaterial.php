<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id', 'type', 'title', 'description', 'content',
        'file_path', 'file_type', 'video_url', 'video_source',
        'duration_minutes', 'order', 'is_downloadable',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'order' => 'integer',
            'is_downloadable' => 'boolean',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function completedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_material_user')->withTimestamps();
    }

    /**
     * Get the embed URL for YouTube/Vimeo videos.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->type !== 'video' || !$this->video_url) {
            return null;
        }

        if ($this->video_source === 'youtube') {
            // Extract YouTube video ID
            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $matches);
            return isset($matches[1]) ? "https://www.youtube.com/embed/{$matches[1]}" : $this->video_url;
        }

        if ($this->video_source === 'vimeo') {
            // Extract Vimeo video ID
            preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches);
            return isset($matches[1]) ? "https://player.vimeo.com/video/{$matches[1]}" : $this->video_url;
        }

        return null;
    }

    /**
     * Determine if this material is a video with an uploaded file.
     */
    public function getIsUploadedVideoAttribute(): bool
    {
        return $this->type === 'video' && $this->video_source === 'upload' && $this->file_path;
    }

    /**
     * Get the icon class based on material type.
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'video' => '🎬',
            'documento' => '📄',
            'presentacion' => '📊',
            'texto' => '📝',
            'recurso' => '📦',
            default => '📎',
        };
    }
}
