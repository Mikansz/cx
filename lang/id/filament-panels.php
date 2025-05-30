<?php

return [
    'pages' => [
        'dashboard' => [
            'title' => 'Dasbor',
        ],
        'login' => [
            'title' => 'Masuk',
            'heading' => 'Masuk ke akun Anda',
            'form' => [
                'email' => 'Email',
                'password' => 'Kata Sandi',
                'remember' => 'Ingat saya',
                'submit' => 'Masuk',
            ],
            'register' => [
                'before' => 'Belum memiliki akun?',
                'label' => 'Daftar di sini',
            ],
            'forgot_password' => [
                'label' => 'Lupa kata sandi?',
            ],
        ],
        'register' => [
            'title' => 'Daftar',
            'heading' => 'Buat akun baru',
            'form' => [
                'name' => 'Nama',
                'email' => 'Email',
                'password' => 'Kata Sandi',
                'password_confirmation' => 'Konfirmasi Kata Sandi',
                'submit' => 'Daftar',
            ],
            'login' => [
                'before' => 'Sudah memiliki akun?',
                'label' => 'Masuk di sini',
            ],
        ],
        'forgot_password' => [
            'title' => 'Lupa kata sandi',
            'heading' => 'Reset kata sandi Anda',
            'form' => [
                'email' => 'Email',
                'submit' => 'Kirim tautan reset',
            ],
            'login' => [
                'before' => 'Ingat kata sandi Anda?',
                'label' => 'Kembali ke halaman masuk',
            ],
            'notification' => 'Tautan reset kata sandi telah dikirim!',
        ],
        'reset_password' => [
            'title' => 'Reset kata sandi',
            'heading' => 'Atur kata sandi baru',
            'form' => [
                'password' => 'Kata Sandi Baru',
                'password_confirmation' => 'Konfirmasi Kata Sandi Baru',
                'submit' => 'Reset kata sandi',
            ],
            'notification' => 'Kata sandi Anda telah direset!',
        ],
        'profile' => [
            'title' => 'Profil',
            'heading' => 'Profil Anda',
            'form' => [
                'submit' => 'Simpan perubahan',
            ],
            'password' => [
                'heading' => 'Kata Sandi',
                'form' => [
                    'current_password' => 'Kata Sandi Saat Ini',
                    'new_password' => 'Kata Sandi Baru',
                    'new_password_confirmation' => 'Konfirmasi Kata Sandi Baru',
                    'submit' => 'Perbarui kata sandi',
                ],
                'notification' => 'Kata sandi Anda telah diperbarui!',
            ],
        ],
    ],
    'navigation' => [
        'group' => [
            'users' => 'Pengguna',
            'settings' => 'Pengaturan',
        ],
        'item' => [
            'dashboard' => 'Dasbor',
            'logout' => 'Keluar',
            'profile' => 'Profil',
        ],
    ],
    'widgets' => [
        'account' => [
            'label' => 'Akun',
            'logout' => 'Keluar',
            'profile' => 'Profil',
        ],
    ],
]; 