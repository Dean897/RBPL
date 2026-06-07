<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TamuQr - Manajemen Data Tamu dengan QR Code
 *
 * Fitur:
 * - Generate QR Code unik untuk setiap tamu
 * - Tracking check-in/check-out tamu
 * - Status: terdaftar, hadir, tidak_hadir
 */
class TamuQr extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tamu_qrs';

    /**
     * Atribut yang dapat diisi
     */
    protected $fillable = [
        'qr_code',
        'nama_tamu',
        'asal_instansi',
        'tujuan_kunjungan',
        'email',
        'no_telepon',
        'waktu_registrasi',
        'waktu_check_in',
        'status',
        'foto_qr',
        'created_by',
        'updated_by',
    ];

    /**
     * Cast attribute
     */
    protected $casts = [
        'waktu_registrasi' => 'datetime',
        'waktu_check_in' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────────────────

    /**
     * Relasi ke User (pembuat record)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User (peng-update record)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ─────────────────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────────────────

    /**
     * Scope: Filter tamu yang sudah check-in
     */
    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'hadir');
    }

    /**
     * Scope: Filter tamu yang terdaftar tapi belum check-in
     */
    public function scopeRegistered($query)
    {
        return $query->where('status', 'terdaftar');
    }

    /**
     * Scope: Filter tamu yang tidak hadir
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'tidak_hadir');
    }

    /**
     * Scope: Filter tamu hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('waktu_registrasi', today());
    }

    /**
     * Scope: Filter berdasarkan asal instansi
     */
    public function scopeFromInstitution($query, string $institution)
    {
        return $query->where('asal_instansi', 'like', "%{$institution}%");
    }

    /**
     * Scope: Filter berdasarkan tujuan kunjungan
     */
    public function scopeForPurpose($query, string $purpose)
    {
        return $query->where('tujuan_kunjungan', 'like', "%{$purpose}%");
    }

    // ─────────────────────────────────────────────────────────
    // Methods
    // ─────────────────────────────────────────────────────────

    /**
     * Cek apakah tamu sudah check-in
     */
    public function isCheckedIn(): bool
    {
        return $this->status === 'hadir';
    }

    /**
     * Cek apakah tamu terdaftar (belum check-in)
     */
    public function isRegistered(): bool
    {
        return $this->status === 'terdaftar';
    }

    /**
     * Mark tamu sebagai hadir (check-in)
     */
    public function markAsCheckedIn(int $userId): void
    {
        $this->update([
            'status' => 'hadir',
            'waktu_check_in' => now(),
            'updated_by' => $userId,
        ]);
    }

    /**
     * Mark tamu sebagai tidak hadir
     */
    public function markAsAbsent(int $userId): void
    {
        $this->update([
            'status' => 'tidak_hadir',
            'updated_by' => $userId,
        ]);
    }

    /**
     * Dapatkan waktu kunjungan dalam format readable
     */
    public function getVisitTimeAttribute(): string
    {
        return $this->waktu_registrasi?->diffForHumans() ?? 'N/A';
    }

    /**
     * Dapatkan durasi kunjungan dalam menit (jika sudah check-in)
     */
    public function getDurationMinutesAttribute(): ?int
    {
        if (!$this->waktu_check_in) {
            return null;
        }

        return $this->waktu_registrasi->diffInMinutes($this->waktu_check_in);
    }
}
