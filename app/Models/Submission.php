<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    // TODO 2: Tambahkan 'nilai' dan 'catatan_dosen' ke $fillable
    protected $fillable = [
        'assignment_id',
        'user_id',
        'jawaban',
        'nilai',
        'catatan_dosen',
        'submitted_at',
    ];

    protected $casts = [
        'nilai' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusNilaiAttribute(): string
    {
        // TODO 2: Logika status nilai
        if ($this->nilai === null) {
            return 'Belum Dinilai';
        }

        if ($this->nilai >= 70) {
            return 'Lulus';
        }

        return 'Perlu Perbaikan';
    }
}