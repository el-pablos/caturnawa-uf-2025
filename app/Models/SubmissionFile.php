<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model untuk file submission
 * 
 * @property int $id
 * @property int $submission_id
 * @property string $filename
 * @property string $original_name
 * @property string $file_path
 * @property string $mime_type
 * @property int $file_size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SubmissionFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submission_id',
        'filename',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the submission that owns the file.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is an image
     */
    public function getIsImageAttribute()
    {
        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($this->mime_type, $imageTypes);
    }

    /**
     * Check if file is a document
     */
    public function getIsDocumentAttribute()
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
        return in_array($this->mime_type, $documentTypes);
    }

    /**
     * Get file icon based on type
     */
    public function getFileIconAttribute()
    {
        if ($this->is_image) {
            return 'bi-image';
        }

        switch ($this->mime_type) {
            case 'application/pdf':
                return 'bi-file-earmark-pdf';
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'bi-file-earmark-word';
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'bi-file-earmark-excel';
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                return 'bi-file-earmark-ppt';
            case 'application/zip':
            case 'application/x-rar-compressed':
                return 'bi-file-earmark-zip';
            default:
                return 'bi-file-earmark';
        }
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute()
    {
        return route('download.submission', [
            'submission' => $this->submission_id,
            'filename' => $this->filename
        ]);
    }

    /**
     * Check if file exists in storage
     */
    public function exists()
    {
        return Storage::disk('private')->exists($this->file_path);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile()
    {
        if ($this->exists()) {
            Storage::disk('private')->delete($this->file_path);
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete file from storage when model is deleted
        static::deleting(function ($file) {
            $file->deleteFile();
        });
    }
}
