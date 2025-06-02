<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $karyawan->kode_karyawan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .slip-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .employee-info {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th,
        .salary-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .salary-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .amount {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .summary {
            margin-top: 20px;
        }
        .total-gaji {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            background-color: #e8f5e8;
            padding: 10px;
            border: 2px solid #4CAF50;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
        }
        .print-date {
            font-size: 10px;
            color: #666;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">PT. RESTU HATI INDONESIA</div>
        <div>Jl. Contoh No. 123, Jakarta</div>
        <div class="slip-title">SLIP GAJI KARYAWAN</div>
        <div>Periode: {{ $bulan }}</div>
    </div>

    <div class="employee-info">
        <div class="info-row">
            <div class="info-label">Kode Karyawan:</div>
            <div class="info-value">{{ $karyawan->kode_karyawan }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama:</div>
            <div class="info-value">{{ $karyawan->user->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">NIP:</div>
            <div class="info-value">{{ $karyawan->nip }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jabatan:</div>
            <div class="info-value">{{ $karyawan->jabatan->nama_jabatan ?? 'N/A' }}</div>
        </div>
    </div>

    <table class="salary-table">
        <thead>
            <tr>
                <th>KOMPONEN GAJI</th>
                <th class="amount">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gaji Pokok -->
            <tr>
                <td>Gaji Pokok</td>
                <td class="amount">{{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            
            <!-- Tunjangan -->
            @if($penggajian->tunjangan_transport > 0)
            <tr>
                <td>Tunjangan Transportasi</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_transport, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_makan > 0)
            <tr>
                <td>Tunjangan Makan</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_makan, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_komunikasi > 0)
            <tr>
                <td>Tunjangan Komunikasi</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_komunikasi, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_kesehatan > 0)
            <tr>
                <td>Tunjangan Kesehatan</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_kesehatan, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_lembur > 0)
            <tr>
                <td>Tunjangan Lembur</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_lembur, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_hari_raya > 0)
            <tr>
                <td>Tunjangan Hari Raya</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_hari_raya, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_insentif > 0)
            <tr>
                <td>Tunjangan Insentif</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_insentif, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->tunjangan_lainnya > 0)
            <tr>
                <td>Tunjangan Lainnya</td>
                <td class="amount">{{ number_format($penggajian->tunjangan_lainnya, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            <!-- Potongan -->
            @if($penggajian->potongan_kasbon > 0)
            <tr>
                <td>Potongan Kasbon</td>
                <td class="amount">-{{ number_format($penggajian->potongan_kasbon, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->potongan_tidak_hadir > 0)
            <tr>
                <td>Potongan Tidak Hadir</td>
                <td class="amount">-{{ number_format($penggajian->potongan_tidak_hadir, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->potongan_penyesuaian_lainnya > 0)
            <tr>
                <td>Potongan Penyesuaian Lainnya</td>
                <td class="amount">-{{ number_format($penggajian->potongan_penyesuaian_lainnya, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            @if($penggajian->potongan_pph21 > 0)
            <tr>
                <td>Potongan PPh21</td>
                <td class="amount">-{{ number_format($penggajian->potongan_pph21, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            <!-- Total -->
            <tr class="total-row">
                <td><strong>TOTAL GAJI BERSIH</strong></td>
                <td class="amount"><strong>{{ number_format($penggajian->total_gaji, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="total-gaji">
        TOTAL GAJI YANG DITERIMA: Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
    </div>

    @if($penggajian->keterangan)
    <div class="summary">
        <strong>Keterangan:</strong><br>
        {{ $penggajian->keterangan }}
    </div>
    @endif

    @if($penggajian->approval_note)
    <div class="summary">
        <strong>Catatan Persetujuan:</strong><br>
        {{ $penggajian->approval_note }}
    </div>
    @endif

    <div class="footer">
        <div>Jakarta, {{ $penggajian->approved_at ? $penggajian->approved_at->format('d F Y') : now()->format('d F Y') }}</div>
        <div style="margin-top: 10px;">
            @if($penggajian->approvedBy)
                <div>Disetujui oleh: {{ $penggajian->approvedBy->name }}</div>
            @endif
        </div>
        
        <div class="signature">
            <div style="display: inline-block; text-align: center; margin-right: 100px;">
                <div style="margin-bottom: 60px;">Mengetahui,</div>
                <div>HRD</div>
            </div>
            <div style="display: inline-block; text-align: center;">
                <div style="margin-bottom: 60px;">Karyawan,</div>
                <div>{{ $karyawan->user->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="print-date">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>
