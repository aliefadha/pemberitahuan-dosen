<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokumen extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe_dokumen',
        'tanggal_deadline',
    ];

    protected $casts = [
        'tanggal_deadline' => 'datetime',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(DokumenSubmission::class);
    }

    public function isDeadlinePassed(): bool
    {
        return $this->tanggal_deadline->isPast();
    }

    public function getSubmissionCountAttribute(): int
    {
        return $this->submissions()->count();
    }
}
