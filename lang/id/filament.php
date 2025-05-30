<?php

return [
    // Terjemahan labels umum
    'common' => [
        'actions' => [
            'create' => 'Buat',
            'delete' => 'Hapus',
            'edit' => 'Edit',
            'view' => 'Lihat',
            'save' => 'Simpan',
            'cancel' => 'Batal',
        ],
        'columns' => [
            'name' => 'Nama',
            'email' => 'Email',
            'created_at' => 'Dibuat Pada',
            'updated_at' => 'Diperbarui Pada',
        ],
    ],
    
    // Terjemahan untuk navigasi
    'navigation' => [
        'users' => 'Pengguna',
        'shifts' => 'Shift',
        'schedules' => 'Jadwal',
        'pegawai' => 'Karyawan',
        'offices' => 'Lokasi Kantor',
        'leaves' => 'Cuti',
        'attendances' => 'Absensi',
    ],
    
    // Terjemahan untuk resource users
    'resources' => [
        'users' => [
            'label' => 'Pengguna',
            'plural_label' => 'Pengguna',
            'fields' => [
                'name' => 'Nama',
                'email' => 'Email',
                'password' => 'Kata Sandi',
                'roles' => 'Peran',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
        ],
        'shifts' => [
            'label' => 'Shift',
            'plural_label' => 'Shift',
            'fields' => [
                'name' => 'Nama',
                'start_time' => 'Waktu Mulai',
                'end_time' => 'Waktu Selesai',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
        ],
        'schedules' => [
            'label' => 'Jadwal',
            'plural_label' => 'Jadwal',
            'fields' => [
                'pegawai' => 'Karyawan',
                'shift' => 'Shift',
                'date' => 'Tanggal',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
        ],
        'pegawai' => [
            'label' => 'Karyawan',
            'plural_label' => 'Karyawan',
            'fields' => [
                'name' => 'Nama',
                'email' => 'Email',
                'phone' => 'Telepon',
                'address' => 'Alamat',
                'office' => 'Kantor',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
        ],
        'offices' => [
            'label' => 'Lokasi Kantor',
            'plural_label' => 'Lokasi Kantor',
            'fields' => [
                'name' => 'Nama',
                'address' => 'Alamat',
                'phone' => 'Telepon',
                'latitude' => 'Garis Lintang',
                'longitude' => 'Garis Bujur',
                'radius' => 'Radius',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
        ],
        'leaves' => [
            'label' => 'Cuti',
            'plural_label' => 'Cuti',
            'fields' => [
                'pegawai' => 'Karyawan',
                'leave_type' => 'Jenis Cuti',
                'start_date' => 'Tanggal Mulai',
                'end_date' => 'Tanggal Selesai',
                'reason' => 'Alasan',
                'status' => 'Status',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
            'leave_types' => [
                'cuti_tahunan' => 'Cuti Tahunan',
                'cuti_sakit' => 'Cuti Sakit',
                'cuti_melahirkan' => 'Cuti Melahirkan',
                'cuti_penting' => 'Cuti Penting',
                'cuti_besar' => 'Cuti Besar',
            ],
        ],
        'attendances' => [
            'label' => 'Absensi',
            'plural_label' => 'Absensi',
            'fields' => [
                'pegawai' => 'Karyawan',
                'date' => 'Tanggal',
                'check_in' => 'Jam Masuk',
                'check_out' => 'Jam Keluar',
                'status' => 'Status',
                'created_at' => 'Dibuat Pada',
                'updated_at' => 'Diperbarui Pada',
            ],
        ],
    ],
]; 