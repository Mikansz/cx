<?php

return [
    'columns' => [
        'text' => [
            'more_list_items' => 'dan :count lainnya',
        ],
    ],
    'actions' => [
        'disable_reordering' => [
            'label' => 'Selesai mengatur ulang catatan',
        ],
        'enable_reordering' => [
            'label' => 'Atur ulang catatan',
        ],
        'filter' => [
            'label' => 'Filter',
        ],
        'open_bulk_actions' => [
            'label' => 'Buka tindakan',
        ],
        'toggle_columns' => [
            'label' => 'Beralih kolom',
        ],
    ],
    'empty' => [
        'heading' => 'Tidak ada data',
        'description' => 'Buat :model untuk memulai.',
    ],
    'filters' => [
        'actions' => [
            'remove' => [
                'label' => 'Hapus filter',
            ],
            'remove_all' => [
                'label' => 'Hapus semua filter',
                'tooltip' => 'Hapus semua filter',
            ],
            'reset' => [
                'label' => 'Reset filter',
            ],
        ],
        'indicator' => 'Filter aktif',
        'multi_select' => [
            'placeholder' => 'Semua',
        ],
        'select' => [
            'placeholder' => 'Semua',
        ],
        'trashed' => [
            'label' => 'Catatan yang dihapus',
            'options' => [
                'only_trashed' => 'Hanya catatan yang dihapus',
                'with_trashed' => 'Dengan catatan yang dihapus',
                'without_trashed' => 'Tanpa catatan yang dihapus',
            ],
        ],
    ],
    'pagination' => [
        'fields' => [
            'records_per_page' => [
                'label' => 'per halaman',
            ],
        ],
        'overview' => 'Menampilkan :first sampai :last dari :total hasil',
    ],
    'search' => [
        'label' => 'Cari',
        'placeholder' => 'Cari',
    ],
    'summary' => [
        'heading' => 'Ringkasan',
    ],
]; 