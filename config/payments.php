<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rekening Tujuan Transfer
    |--------------------------------------------------------------------------
    |
    | Daftar rekening yang ditampilkan ke pelanggan pada halaman pembayaran.
    | Sensitif terhadap spasi; isi lewat environment agar mudah diubah.
    |
    */

    'bank_accounts' => [
        [
            'bank' => env('PAYMENT_BANK_NAME', 'BCA'),
            'number' => env('PAYMENT_BANK_NUMBER', '1234567890'),
            'holder' => env('PAYMENT_BANK_HOLDER', 'PT OmniSync Commerce'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Penyimpanan Bukti Transfer
    |--------------------------------------------------------------------------
    */

    'proof_disk' => env('PAYMENT_PROOF_DISK', 'local'),

    'proof_directory' => 'payment-proofs',

    'proof_max_kilobytes' => (int) env('PAYMENT_PROOF_MAX_KB', 5120),

];
