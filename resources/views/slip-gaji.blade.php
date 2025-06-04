<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $karyawan->kode_karyawan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 15px;
            color: #000;
            background: white;
        }
        .slip-container {
            border: 2px solid #000;
            padding: 15px;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .company-logo {
            width: 80px;
            height: 60px;
            background: #D2B48C;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            text-align: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .header-content {
            flex: 1;
        }
        .slip-title {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 10px;
        }
        .company-address {
            font-size: 10px;
            line-height: 1.2;
            margin-bottom: 5px;
        }
        .employee-info {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .employee-left, .employee-right {
            font-size: 10px;
            line-height: 1.5;
        }
        .salary-section {
            margin-bottom: 15px;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .section-header {
            background-color: #2C5530;
            color: white;
            text-align: center;
            font-weight: bold;
            padding: 5px;
            font-size: 10px;
        }
        .salary-table td {
            border: 1px solid #666;
            padding: 3px 8px;
            vertical-align: top;
        }
        .amount {
            text-align: right;
            width: 100px;
        }
        .total-pendapatan {
            background-color: #2C5530;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            margin: 10px 0;
            font-size: 11px;
        }
        .attendance-section {
            margin-top: 15px;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .attendance-table td {
            border: 1px solid #666;
            padding: 3px 8px;
        }
        .notes-section {
            margin-top: 15px;
        }
        .notes-header {
            background-color: #2C5530;
            color: white;
            padding: 5px;
            font-weight: bold;
            font-size: 10px;
        }
        .notes-content {
            border: 1px solid #666;
            border-top: none;
            min-height: 40px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <div class="company-logo">
                <div>
                    <div style="font-weight: bold; font-size: 6px;">RUMAH</div>
                    <div style="font-size: 5px;">KERJA</div>
                </div>
            </div>
            <div class="header-content">
                <div class="slip-title">SLIP GAJI</div>
                <div class="company-address">
                    Jl. Sempur Kaler No 15<br>
                    Kota Bogor
                </div>
            </div>
        </div>

        <div class="employee-info">
            <div class="employee-left">
                <div>Nama Karyawan: {{ $karyawan->user->name ?? 'N/A' }}</div>
                <div>Dept / Jabatan: {{ $karyawan->jabatan->nama_jabatan ?? 'N/A' }}</div>
                <div>Periode: {{ $bulan }}</div>
            </div>
            <div class="employee-right">
                <div>Srl: {{ $karyawan->kode_karyawan }}</div>
                <div>Tanggal Gaji: {{ now()->format('d-M-Y') }}</div>
            </div>
        </div>

        <div class="salary-section">
            <table class="salary-table">
                <tr>
                    <td class="section-header">PENDAPATAN</td>
                    <td class="section-header">POTONGAN</td>
                </tr>
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Gaji Pokok</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Tunjangan</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format(($penggajian->tunjangan_transport ?? 0) + ($penggajian->tunjangan_makan ?? 0) + ($penggajian->tunjangan_komunikasi ?? 0) + ($penggajian->tunjangan_kesehatan ?? 0) + ($penggajian->tunjangan_lembur ?? 0) + ($penggajian->tunjangan_hari_raya ?? 0) + ($penggajian->tunjangan_insentif ?? 0) + ($penggajian->tunjangan_lainnya ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Lembur</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_lembur ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Transport</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_transport ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Uang Makan</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_makan ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Komunikasi</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_komunikasi ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Kesehatan</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_kesehatan ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Hari Raya</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_hari_raya ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Insentif / Bonus</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_insentif ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Tunj Penyesuaian Lainnya</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->tunjangan_lainnya ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Kasbon</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->potongan_kasbon ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Absen</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->potongan_absen ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Tidak Hadir</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->potongan_tidak_hadir ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">PPh 21</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->potongan_pph21 ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 2px 5px;">Pot Penyesuaian Lainnya</td>
                                <td style="border: none; padding: 2px 5px; text-align: right;">{{ number_format($penggajian->potongan_penyesuaian_lainnya ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr><td style="border: none; padding: 2px 5px;">&nbsp;</td><td style="border: none; padding: 2px 5px;">&nbsp;</td></tr>
                            <tr><td style="border: none; padding: 2px 5px;">&nbsp;</td><td style="border: none; padding: 2px 5px;">&nbsp;</td></tr>
                            <tr><td style="border: none; padding: 2px 5px;">&nbsp;</td><td style="border: none; padding: 2px 5px;">&nbsp;</td></tr>
                            <tr><td style="border: none; padding: 2px 5px;">&nbsp;</td><td style="border: none; padding: 2px 5px;">&nbsp;</td></tr>
                            <tr><td style="border: none; padding: 2px 5px;">&nbsp;</td><td style="border: none; padding: 2px 5px;">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 2px solid #000; padding: 5px; font-weight: bold;">
                        Total Pendapatan: {{ number_format(($penggajian->gaji_pokok ?? 0) + ($penggajian->tunjangan_transport ?? 0) + ($penggajian->tunjangan_makan ?? 0) + ($penggajian->tunjangan_komunikasi ?? 0) + ($penggajian->tunjangan_kesehatan ?? 0) + ($penggajian->tunjangan_lembur ?? 0) + ($penggajian->tunjangan_hari_raya ?? 0) + ($penggajian->tunjangan_insentif ?? 0) + ($penggajian->tunjangan_lainnya ?? 0), 0, ',', '.') }}
                    </td>
                    <td style="border-top: 2px solid #000; padding: 5px; font-weight: bold;">
                        Total Potongan: {{ number_format(($penggajian->potongan_kasbon ?? 0) + ($penggajian->potongan_absen ?? 0) + ($penggajian->potongan_tidak_hadir ?? 0) + ($penggajian->potongan_pph21 ?? 0) + ($penggajian->potongan_penyesuaian_lainnya ?? 0), 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="total-pendapatan">
            Total Penerimaan Bulan Ini<br>
            Rp {{ number_format($penggajian->total_gaji ?? 0, 0, ',', '.') }}
        </div>

        <div class="attendance-section">
            <table class="attendance-table">
                <tr>
                    <td class="section-header" colspan="4">Ringkasan Informasi Kehadiran</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Kehadiran</td>
                    <td>{{ $attendanceData['total_present'] ?? 0 }}</td>
                    <td style="font-weight: bold;">Total Telat</td>
                    <td>{{ $attendanceData['total_late'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ketidak Hadiran</td>
                    <td>{{ $attendanceData['total_absent'] ?? 0 }}</td>
                    <td style="font-weight: bold;">Pulang Lebih Dulu</td>
                    <td>{{ $attendanceData['total_early_leave'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Cuti</td>
                    <td>{{ $attendanceData['total_cuti'] ?? 0 }}</td>
                    <td style="font-weight: bold;">Total Tidak Absen</td>
                    <td>{{ $attendanceData['total_absent'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sakit</td>
                    <td>{{ $attendanceData['total_sakit'] ?? 0 }}</td>
                    <td style="font-weight: bold;">Izin</td>
                    <td>{{ $attendanceData['total_izin'] ?? 0 }}</td>
                </tr>
            </table>
        </div>

        <div class="notes-section">
            <div class="notes-header">Rincian</div>
            <div class="notes-content">
                @if($penggajian->keterangan)
                    <strong>Keterangan:</strong> {{ $penggajian->keterangan }}<br>
                @endif
                @if($penggajian->approval_note)
                    <strong>Catatan:</strong> {{ $penggajian->approval_note }}<br>
                @endif
                @if(isset($attendanceData['total_overtime_hours']) && $attendanceData['total_overtime_hours'] > 0)
                    <strong>Total Jam Lembur:</strong> {{ number_format($attendanceData['total_overtime_hours'], 1) }} jam<br>
                @endif
                <strong>Hari Kerja Efektif:</strong> {{ $attendanceData['total_working_days'] ?? 22 }} hari
            </div>
        </div>
    </div>
</body>
</html>
