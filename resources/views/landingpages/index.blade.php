@php
    $register = url('/register');

    $steps = [
        [
            '1',
            'Upload 3 File Excel',
            'Unggah file template Campaign Marketplace (Tokopedia/Shopee), database Internal SKU Master, dan file Target Diskon ke zona drag-and-drop.',
            'Mendukung .XLSX & .CSV hingga 50MB',
            'M12 16V4M7 9l5-5 5 5M4 20h16',
        ],
        [
            '2',
            'Sistem Cocokkan SKU & Bundle',
            'Queue worker memproses fuzzy algorithm & aturan part number bundle secara otomatis, memvalidasi stok dan harga dasar.',
            'Pemrosesan presisi 99.6% tanpa jeda',
            'M4 12a8 8 0 0114-5l2-2v6h-6l2.3-2.3A5 5 0 007 12M20 12a8 8 0 01-14 5l-2 2v-6h6l-2.3 2.3A5 5 0 0017 12',
        ],
        [
            '3',
            'Download Output Siap Pakai',
            'Unduh lembar kerja akhir yang telah disesuaikan format aslinya, langsung impor ke Seller Center tanpa error validasi kolom.',
            'Garansi kompatibilitas Tokopedia & Shopee',
            'M12 4v12M7 11l5 5 5-5M4 20h16',
        ],
    ];

    $trust = [
        [
            'Garansi Keamanan Data',
            'Data file Excel dienkripsi dan dihapus berkala sesuai kebijakan retensi.',
            'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z',
        ],
        [
            'Auto SKU Reconciliation',
            'Deteksi instan stok nyata, SKU kosong, dan kesalahan harga varian sebelum submit.',
            'M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5L18 18M18 6l-2.5 2.5M8.5 15.5L6 18',
        ],
        [
            'Tanpa Biaya Tersembunyi',
            'Harga tertera sudah mencakup seluruh fitur paket. Tidak ada potongan per order marketplace.',
            'M3 7h18v10H3zM12 12h.01M7 12h.01M17 12h.01',
        ],
    ];

    $faqs = [
        [
            'Format file apa saja yang didukung oleh OmniSync?',
            'OmniSync mendukung file .XLSX, .XLS, dan .CSV hingga 50MB per file. File sumber tidak pernah diubah; data diimpor ke database lalu diproses dari sana.',
        ],
        [
            'Bagaimana jika terdapat SKU marketplace yang belum terdaftar internal (NOT_FOUND)?',
            'SKU tersebut ditandai NOT_FOUND di halaman preview dan tidak dimasukkan ke file campaign final. Anda bisa melengkapi data internal lalu memproses ulang.',
        ],
        [
            'Apakah data marketplace dan rahasia harga perusahaan saya aman?',
            'Ya. File disimpan di storage privat, dienkripsi, dan hanya bisa diunduh oleh akun yang berwenang. File dihapus berkala sesuai kebijakan retensi.',
        ],
        [
            'Bagaimana cara upgrade atau berhenti dari Paket Pembelian?',
            'Anda dapat mengganti atau menghentikan paket kapan saja dari portal admin. Perubahan berlaku pada siklus tagihan berikutnya.',
        ],
    ];

    $footLinks = [
        'Dokumentasi API',
        'Integrasi Data Tokopedia',
        'Sinkronisasi Shopee',
        'Status Sistem',
        'Syarat & Ketentuan',
        'Kebijakan Privasi',
    ];
@endphp

<x-landing.layout>
    <x-landing.navbar />
    <x-landing.hero />
    <x-landing.steps :steps="$steps" />
    <x-landing.plans :plans="$plans ?? []" :trust="$trust" :register="$register" />
    <x-landing.features />
    <x-landing.faq :faqs="$faqs" />
    <x-landing.cta :register="$register" />
    <x-landing.footer :footLinks="$footLinks" />
</x-landing.layout>
