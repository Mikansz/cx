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
            line-height: 1.4;
        }
        .slip-container {
            border: 2px solid #000;
            padding: 20px;
            max-width: 650px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 3px solid #2C5530;
            padding-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .company-logo {
            width: 90px;
            height: 70px;
            background: linear-gradient(135deg, #2C5530, #4a7c59);
            border: 2px solid #2C5530;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            text-align: center;
            margin-right: 25px;
            flex-shrink: 0;
            color: white;
            border-radius: 5px;
        }
        .header-content {
            flex: 1;
        }
        .slip-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 12px;
            color: #2C5530;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .slip-number {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
            color: #2C5530;
            background: #e8f5e8;
            padding: 5px 15px;
            border-radius: 15px;
            display: inline-block;
        }
        .company-address {
            font-size: 10px;
            line-height: 1.3;
            margin-top: 5px;
        }
        .content-section {
            margin-bottom: 25px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-title {
            background: linear-gradient(135deg, #2C5530, #4a7c59);
            color: white;
            padding: 12px 18px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .info-table td {
            border: none;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 18px;
            vertical-align: top;
        }
        .info-label {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 35%;
            color: #2C5530;
        }
        .leave-type-badge {
            background: linear-gradient(135deg, #e8f5e8, #d4edda);
            color: #2C5530;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            border: 1px solid #2C5530;
        }
        .status-approved {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            border: 1px solid #155724;
        }
        .footer-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .approval-info {
            flex: 1;
            padding-right: 25px;
        }
        .signature-area {
            text-align: center;
            min-width: 220px;
            border-left: 2px solid #e0e0e0;
            padding-left: 20px;
        }
        .signature-line {
            border-bottom: 2px solid #2C5530;
            width: 160px;
            margin: 70px auto 15px auto;
        }
        .print-info {
            font-size: 10px;
            color: #666;
            text-align: center;
            margin-top: 25px;
            border-top: 2px solid #e0e0e0;
            padding-top: 15px;
            background: white;
            border-radius: 8px;
            padding: 15px;
        }
        .important-note {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 2px solid #ffeaa7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 11px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <div class="company-logo">
                <div>
                    <div style="font-weight: bold; font-size: 10px;">PT</div>
                    <div style="font-size: 8px;">COMPANY</div>
                    <div style="font-size: 6px;">LOGO</div>
                </div>
            </div>
            <div class="header-content">
                <div class="slip-title">SURAT KETERANGAN CUTI</div>
                <div style="text-align: center; margin: 10px 0;">
                    <span class="slip-number">Nomor: CT/{{ str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</span>
                </div>
                <div class="company-address" style="text-align: center; font-size: 11px; color: #666;">
                    Jl. Contoh Alamat No. 123, Jakarta<br>
                    Telp: (021) 123-4567 | Email: hr@company.com
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
            <div class="section-title">KETERANGAN RESMI</div>
            <div style="padding: 18px;">
                <p style="margin: 0; line-height: 1.8; text-align: justify; font-size: 13px;">
                    Dengan ini menerangkan bahwa karyawan yang bersangkutan telah diberikan izin cuti sesuai dengan 
                    ketentuan dan peraturan yang berlaku di perusahaan. Cuti ini berlaku efektif mulai tanggal 
                    <strong style="color: #2C5530;">{{ $start_date->format('d F Y') }}</strong> sampai dengan 
                    <strong style="color: #2C5530;">{{ $end_date->format('d F Y') }}</strong>.
                </p>
            </div>
        </div>

        <div class="footer-section">
            <div class="approval-info">
                <h4 style="margin: 0 0 15px 0; color: #2C5530; font-size: 14px;">Informasi Persetujuan</h4>
                <p style="margin: 0 0 10px 0; font-size: 13px;"><strong>Disetujui pada:</strong> {{ $leave->approved_at ? $leave->approved_at->format('d F Y, H:i') : $date }}</p>
                @if($leave->approvedBy)
                <p style="margin: 0 0 10px 0; font-size: 13px;"><strong>Disetujui oleh:</strong> {{ $leave->approvedBy->name }}</p>
                @endif
                @if($leave->note)
                <p style="margin: 0; font-size: 12px; color: #666;"><strong>Catatan:</strong> {{ $leave->note }}</p>
                @endif
            </div>
            <div class="signature-area">
                <div style="font-size: 13px; margin-bottom: 10px;">Jakarta, {{ $date }}</div>
                <div style="margin-top: 5px; font-weight: bold; color: #2C5530;">HRD Manager</div>
                <div class="signature-line"></div>
                <div style="font-weight: bold; font-size: 13px;">{{ $leave->approvedBy->name ?? 'HRD Manager' }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 5px;">Tanda Tangan Digital</div>
            </div>
        </div>

        <div class="print-info">
            <strong style="color: #2C5530;">Dokumen Resmi Perusahaan</strong><br>
            Digenerate secara otomatis pada {{ now()->format('d F Y, H:i:s') }} WIB<br>
            Dokumen ini sah dan memiliki kekuatan hukum yang sama dengan dokumen fisik<br>
            <em style="font-size: 9px;">Verifikasi keaslian dokumen dapat dilakukan melalui sistem HRD</em>
        </div>
    </div>
</body>
</html> 