<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Cuti - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
            color: #000;
            background: white;
        }
        .slip-container {
            border: 2px solid #000;
            padding: 20px;
            max-width: 700px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
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
            margin-right: 20px;
            flex-shrink: 0;
        }
        .header-content {
            flex: 1;
        }
        .slip-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
        }
        .slip-number {
            font-size: 12px;
            text-align: center;
            font-weight: bold;
            color: #666;
        }
        .company-address {
            font-size: 10px;
            line-height: 1.3;
            margin-top: 5px;
        }
        .content-section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #2C5530;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            border: 1px solid #666;
            padding: 8px 12px;
            vertical-align: top;
        }
        .info-label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        .leave-type-badge {
            background-color: #e8f5e8;
            color: #2C5530;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
        }
        .footer-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .approval-info {
            flex: 1;
            padding-right: 20px;
        }
        .signature-area {
            text-align: center;
            min-width: 200px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            width: 150px;
            margin: 60px auto 10px auto;
        }
        .print-info {
            font-size: 9px;
            color: #666;
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 15px 0;
            border-radius: 3px;
            font-size: 10px;
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
                <div class="slip-title">SURAT KETERANGAN CUTI</div>
                <div class="slip-number">Nomor: CT/{{ str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</div>
                <div class="company-address">
                    Jl. Sempur Kaler No 15<br>
                    Kota Bogor<br>
                    Telp: (0251) 123-4567
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-title">INFORMASI KARYAWAN</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Nama Lengkap</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="info-label">NIP / Kode Karyawan</td>
                    <td>{{ $user->karyawan->nip ?? $user->karyawan->kode_karyawan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Jabatan</td>
                    <td>{{ $user->karyawan->jabatan->nama_jabatan ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Departemen</td>
                    <td>{{ $user->roles->first()->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="content-section">
            <div class="section-title">DETAIL CUTI</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Jenis Cuti</td>
                    <td>
                        <span class="leave-type-badge">
                            {{ \App\Models\Leave::getLeaveTypes()[$leave->leave_type] ?? $leave->leave_type }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Tanggal Mulai</td>
                    <td>{{ $start_date->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tanggal Selesai</td>
                    <td>{{ $end_date->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Lama Cuti</td>
                    <td><strong>{{ $start_date->diffInDays($end_date) + 1 }} hari kerja</strong></td>
                </tr>
                <tr>
                    <td class="info-label">Alasan Cuti</td>
                    <td>{{ $leave->reason }}</td>
                </tr>
                <tr>
                    <td class="info-label">Status</td>
                    <td>
                        <span class="status-approved">DISETUJUI</span>
                    </td>
                </tr>
                @if($leave->approval_note)
                <tr>
                    <td class="info-label">Catatan Persetujuan</td>
                    <td>{{ $leave->approval_note }}</td>
                </tr>
                @endif
            </table>
        </div>

        @if($leave->leave_type === 'cuti_sakit' && $leave->sick_certificate)
        <div class="important-note">
            <strong>Catatan:</strong> Cuti sakit ini dilengkapi dengan surat keterangan dokter yang telah diverifikasi oleh HRD.
        </div>
        @endif

        <div class="content-section">
            <p style="margin: 0; line-height: 1.6;">
                Dengan ini menerangkan bahwa karyawan yang bersangkutan telah diberikan izin cuti sesuai dengan 
                ketentuan yang berlaku di perusahaan. Cuti ini berlaku efektif mulai tanggal 
                <strong>{{ $start_date->format('d F Y') }}</strong> sampai dengan 
                <strong>{{ $end_date->format('d F Y') }}</strong>.
            </p>
        </div>

        <div class="footer-section">
            <div class="approval-info">
                <p style="margin: 0 0 10px 0;"><strong>Disetujui pada:</strong> {{ $leave->approved_at ? $leave->approved_at->format('d F Y') : $date }}</p>
                @if($leave->approvedBy)
                <p style="margin: 0;"><strong>Disetujui oleh:</strong> {{ $leave->approvedBy->name }}</p>
                @endif
            </div>
            <div class="signature-area">
                <div>Kota Bogor, {{ $date }}</div>
                <div style="margin-top: 5px;">HRD Manager</div>
                <div class="signature-line"></div>
                <div style="font-weight: bold;">{{ $leave->approvedBy->name ?? 'HRD Manager' }}</div>
            </div>
        </div>

        <div class="print-info">
            Dokumen ini digenerate secara otomatis pada {{ now()->format('d F Y H:i:s') }}<br>
            Dokumen ini sah tanpa tanda tangan basah sesuai ketentuan perusahaan
        </div>
    </div>
</body>
</html> 