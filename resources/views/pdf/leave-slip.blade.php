<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SURAT KETERANGAN CUTI</h1>
        <p>Nomor: CT/{{ $leave->id }}/{{ date('Y') }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

        <table>
            <tr>
                <th width="30%">Nama</th>
                <td>: {{ $user->name }}</td>
            </tr>
            <tr>
                <th>NIP</th>
                <td>: {{ $user->karyawan->nip ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>: {{ $user->roles->first()->name ?? '-' }}</td>
            </tr>
        </table>

        <p>Telah diberikan izin cuti dengan rincian sebagai berikut:</p>

        <table>
            <tr>
                <th width="30%">Tanggal Mulai</th>
                <td>: {{ $start_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Tanggal Selesai</th>
                <td>: {{ $end_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Lama Cuti</th>
                <td>: {{ $start_date->diffInDays($end_date) + 1 }} hari</td>
            </tr>
            <tr>
                <th>Alasan</th>
                <td>: {{ $leave->reason }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ $date }}</p>
        <p>Menyetujui,</p>
        
        <div class="signature">
            <p>________________________</p>
            <p>Manager HRD</p>
        </div>
    </div>
</body>
</html> 