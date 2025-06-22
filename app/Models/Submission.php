<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

/**
 * Model Submission untuk mengelola data submission peserta
 * 
 * Kelas ini menangani semua operasi untuk submission karya peserta
 * termasuk file upload, scoring, dan status tracking
 */
class Submission extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'title',
        'description',
        'files',
        'file_size',
        'submission_notes',
        'is_final',
        'submitted_at',
        'status',
        'file_path',
        'video_url',
        'github_url',
        'preview_image',
        'technologies',
        'team_name',
        'team_members',
        'score',
        'feedback',
        'is_scored',
        'scored_at',
        'view_count',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'files' => 'array',
        'submitted_at' => 'datetime',
        'is_final' => 'boolean',
        'file_size' => 'integer',
        'team_members' => 'array',
        'score' => 'decimal:2',
        'is_scored' => 'boolean',
        'scored_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Atribut yang akan disembunyikan saat serialisasi
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Atribut yang akan ditambahkan ke array/JSON representation
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_label',
        'status_class',
        'file_size_formatted',
    ];

    /**
     * Boot method untuk model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update file_size ketika files berubah
        static::saving(function ($submission) {
            if ($submission->files) {
                $totalSize = 0;
                foreach ($submission->files as $file) {
                    $totalSize += $file['size'] ?? 0;
                }
                $submission->file_size = $totalSize;
            }
        });

        // Set submitted_at ketika is_final berubah menjadi true
        static::saving(function ($submission) {
            if ($submission->is_final && !$submission->getOriginal('is_final')) {
                $submission->submitted_at = now();
                $submission->status = 'submitted';
            }
        });
    }

    /**
     * Relasi dengan model Registration
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Relasi dengan model Competition melalui Registration
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function competition()
    {
        return $this->hasOneThrough(
            Competition::class,
            Registration::class,
            'id',
            'id',
            'registration_id',
            'competition_id'
        );
    }

    /**
     * Relasi dengan model User (peserta) melalui Registration
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Registration::class,
            'id',
            'id',
            'registration_id',
            'user_id'
        );
    }

    /**
     * Relasi dengan model Score
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Relasi dengan model SubmissionComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(SubmissionComment::class);
    }

    /**
     * Relasi dengan model SubmissionFile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(SubmissionFile::class);
    }

    /**
     * Scope untuk submission yang sudah final/disubmit
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    /**
     * Scope untuk submission yang masih draft
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('is_final', false);
    }

    /**
     * Scope untuk submission berdasarkan status
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk submission dalam kompetisi tertentu
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $competitionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCompetition($query, $competitionId)
    {
        return $query->whereHas('registration', function($q) use ($competitionId) {
            $q->where('competition_id', $competitionId);
        });
    }

    /**
     * Scope untuk submission oleh peserta tertentu
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('registration', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Cek apakah submission sudah final
     * 
     * @return bool
     */
    public function isFinal()
    {
        return $this->is_final;
    }

    /**
     * Cek apakah submission masih draft
     * 
     * @return bool
     */
    public function isDraft()
    {
        return !$this->is_final;
    }

    /**
     * Cek apakah deadline submission sudah lewat
     * 
     * @return bool
     */
    public function isOverdue()
    {
        return $this->competition->submission_deadline && 
               now() > $this->competition->submission_deadline;
    }

    /**
     * Validasi apakah submission dapat disubmit
     * 
     * @return bool
     */
    public function canBeSubmitted()
    {
        return !$this->is_final && 
               !$this->isOverdue() && 
               $this->hasRequiredFiles();
    }

    /**
     * Cek apakah semua file yang diperlukan sudah diupload
     * 
     * @return bool
     */
    public function hasRequiredFiles()
    {
        return $this->files && 
               count($this->files) > 0 && 
               !empty($this->title) && 
               !empty($this->description);
    }

    /**
     * Accessor untuk total ukuran file dalam format human readable
     * 
     * @return string
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size ?? 0;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Accessor untuk mendapatkan URL download file
     * 
     * @param string $filename
     * @return string|null
     */
    public function getFileUrl($filename)
    {
        if (!$this->files) {
            return null;
        }
        
        foreach ($this->files as $file) {
            if (($file['filename'] ?? $file['name'] ?? '') === $filename) {
                $path = $file['path'] ?? ('submissions/' . $this->id . '/' . $filename);
                return Storage::disk('public')->url($path);
            }
        }
        
        return null;
    }

    /**
     * Mendapatkan daftar ekstensi file yang diupload
     * 
     * @return array
     */
    public function getFileExtensions()
    {
        $extensions = [];
        
        if ($this->files) {
            foreach ($this->files as $file) {
                $filename = $file['filename'] ?? $file['original_name'] ?? $file['name'] ?? '';
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($ext, $extensions)) {
                    $extensions[] = strtolower($ext);
                }
            }
        }
        
        return $extensions;
    }

    /**
     * Cek apakah ada file dengan ekstensi tertentu
     * 
     * @param string $extension
     * @return bool
     */
    public function hasFileType($extension)
    {
        return in_array(strtolower($extension), $this->getFileExtensions());
    }

    /**
     * Mendapatkan rata-rata skor dari semua juri
     * 
     * @return float
     */
    public function getAverageScore()
    {
        $scores = $this->scores()->where('is_final', true)->get();
        
        if ($scores->count() === 0) {
            return 0;
        }
        
        return $scores->avg('total_score');
    }

    /**
     * Mendapatkan ranking submission dalam kompetisi
     * 
     * @return int|null
     */
    public function getRanking()
    {
        if (!$this->registration || !$this->registration->competition) {
            return null;
        }

        $submissions = Submission::whereHas('registration', function($q) {
                $q->where('competition_id', $this->registration->competition_id);
            })
            ->final()
            ->get()
            ->sortByDesc(function($submission) {
                return $submission->getAverageScore();
            })
            ->values();
            
        foreach ($submissions as $index => $submission) {
            if ($submission->id === $this->id) {
                return $index + 1;
            }
        }
        
        return null;
    }

    /**
     * Cek apakah submission sudah dinilai oleh semua juri
     * 
     * @return bool
     */
    public function isFullyScored()
    {
        $juryCount = User::role('Juri')->count();
        $scoreCount = $this->scores()->where('is_final', true)->count();
        
        return $scoreCount >= $juryCount;
    }

    /**
     * Mendapatkan status submission
     * 
     * @return string
     */
    public function getStatus()
    {
        if (!$this->is_final) {
            return 'draft';
        }
        
        if ($this->isOverdue()) {
            return 'overdue';
        }
        
        if ($this->isFullyScored()) {
            return 'scored';
        }
        
        return 'submitted';
    }

    /**
     * Accessor untuk label status
     * 
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->getStatus()) {
            case 'draft':
                return 'Draft';
            case 'overdue':
                return 'Terlambat';
            case 'scored':
                return 'Sudah Dinilai';
            case 'submitted':
                return 'Terkirim';
            default:
                return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk CSS class status
     * 
     * @return string
     */
    public function getStatusClassAttribute()
    {
        switch ($this->getStatus()) {
            case 'draft':
                return 'secondary';
            case 'overdue':
                return 'danger';
            case 'scored':
                return 'success';
            case 'submitted':
                return 'primary';
            default:
                return 'light';
        }
    }

    /**
     * Submit submission (mengubah status menjadi final)
     * 
     * @return bool
     */
    public function submit()
    {
        if (!$this->canBeSubmitted()) {
            return false;
        }

        $this->is_final = true;
        $this->submitted_at = now();
        $this->status = 'submitted';
        
        return $this->save();
    }

    /**
     * Revert submission kembali ke draft
     * 
     * @return bool
     */
    public function revertToDraft()
    {
        if ($this->isOverdue()) {
            return false;
        }

        $this->is_final = false;
        $this->submitted_at = null;
        $this->status = 'draft';
        
        return $this->save();
    }

    /**
     * Tambah file ke submission
     * 
     * @param array $fileData
     * @return bool
     */
    public function addFile($fileData)
    {
        $files = $this->files ?? [];
        $files[] = $fileData;
        
        $this->files = $files;
        return $this->save();
    }

    /**
     * Hapus file dari submission
     * 
     * @param string $filename
     * @return bool
     */
    public function removeFile($filename)
    {
        if (!$this->files) {
            return false;
        }

        $files = $this->files;
        $updatedFiles = [];
        $removed = false;

        foreach ($files as $file) {
            $currentFilename = $file['filename'] ?? $file['name'] ?? '';
            if ($currentFilename !== $filename) {
                $updatedFiles[] = $file;
            } else {
                // Hapus file dari storage
                $filePath = $file['path'] ?? ('submissions/' . $this->id . '/' . $filename);
                Storage::disk('public')->delete($filePath);
                $removed = true;
            }
        }

        if ($removed) {
            $this->files = $updatedFiles;
            return $this->save();
        }

        return false;
    }

    /**
     * Mendapatkan jumlah file yang diupload
     * 
     * @return int
     */
    public function getFileCount()
    {
        return $this->files ? count($this->files) : 0;
    }

    /**
     * Cek apakah submission memiliki file
     * 
     * @return bool
     */
    public function hasFiles()
    {
        return $this->getFileCount() > 0;
    }

    /**
     * Mendapatkan daftar file untuk download
     * 
     * @return array
     */
    public function getDownloadableFiles()
    {
        $downloadableFiles = [];
        
        if ($this->files) {
            foreach ($this->files as $file) {
                $downloadableFiles[] = [
                    'filename' => $file['filename'] ?? $file['name'] ?? 'Unknown',
                    'original_name' => $file['original_name'] ?? $file['name'] ?? 'Unknown',
                    'size' => $file['size'] ?? 0,
                    'mime_type' => $file['mime_type'] ?? 'application/octet-stream',
                    'url' => $this->getFileUrl($file['filename'] ?? $file['name'] ?? ''),
                    'uploaded_at' => $file['uploaded_at'] ?? $this->created_at,
                ];
            }
        }
        
        return $downloadableFiles;
    }

    /**
     * Validasi file submission
     * 
     * @return array
     */
    public function validateSubmission()
    {
        $errors = [];
        
        if (empty($this->title)) {
            $errors[] = 'Title is required';
        }
        
        if (empty($this->description)) {
            $errors[] = 'Description is required';
        }
        
        if (!$this->hasFiles()) {
            $errors[] = 'At least one file must be uploaded';
        }
        
        if ($this->isOverdue()) {
            $errors[] = 'Submission deadline has passed';
        }
        
        return $errors;
    }

    /**
     * Cek apakah submission valid untuk disubmit
     * 
     * @return bool
     */
    public function isValid()
    {
        return empty($this->validateSubmission());
    }

    /**
     * Mendapatkan waktu tersisa untuk submit
     * 
     * @return \Carbon\Carbon|null
     */
    public function getTimeRemaining()
    {
        if (!$this->registration || !$this->registration->competition || !$this->registration->competition->submission_deadline) {
            return null;
        }
        
        $deadline = $this->registration->competition->submission_deadline;
        
        if (now() >= $deadline) {
            return null;
        }
        
        return $deadline->diffForHumans();
    }

    /**
     * Mendapatkan informasi deadline
     * 
     * @return array
     */
    public function getDeadlineInfo()
    {
        if (!$this->registration || !$this->registration->competition || !$this->registration->competition->submission_deadline) {
            return [
                'has_deadline' => false,
                'deadline' => null,
                'time_remaining' => null,
                'is_overdue' => false,
            ];
        }
        
        $deadline = $this->registration->competition->submission_deadline;
        $now = now();
        
        return [
            'has_deadline' => true,
            'deadline' => $deadline,
            'time_remaining' => $now < $deadline ? $deadline->diffForHumans() : null,
            'is_overdue' => $now >= $deadline,
        ];
    }
}
