<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Leave extends Model
{
    // Konstanta untuk jenis cuti
    public const CUTI_TAHUNAN = 'cuti_tahunan';
    public const CUTI_SAKIT = 'cuti_sakit';
    public const CUTI_MELAHIRKAN = 'cuti_melahirkan';
    public const CUTI_PENTING = 'cuti_penting';
    public const CUTI_BESAR = 'cuti_besar';
    
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'note'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Daftar jenis cuti untuk dropdown
    public static function getLeaveTypes(): array
    {
        return [
            self::CUTI_TAHUNAN => 'Cuti Tahunan',
            self::CUTI_SAKIT => 'Cuti Sakit',
            self::CUTI_MELAHIRKAN => 'Cuti Melahirkan',
            self::CUTI_PENTING => 'Cuti Penting',
            self::CUTI_BESAR => 'Cuti Besar',
        ];
    }
}
