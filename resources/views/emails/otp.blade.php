<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Plus Jakarta Sans',Segoe UI,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px -18px rgba(15,23,42,0.25);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#2563eb,#4f46e5);padding:28px 32px;">
                            <span style="display:inline-flex;align-items:center;gap:8px;color:#ffffff;font-size:16px;font-weight:700;">
                                <span style="display:inline-block;width:28px;height:28px;line-height:28px;text-align:center;background:rgba(255,255,255,0.18);border-radius:8px;font-size:14px;">◈</span>
                                OmniSync Commerce
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px 8px;">
                            <h1 style="margin:0;font-size:20px;font-weight:800;color:#0f172a;">Verifikasi Email Anda</h1>
                            <p style="margin:12px 0 0;font-size:14px;line-height:1.6;color:#64748b;">
                                Gunakan kode berikut untuk menyelesaikan proses masuk atau pendaftaran akun Anda.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px;">
                            <div style="background-color:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:22px;text-align:center;">
                                <p style="margin:0 0 6px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#2563eb;">Kode OTP Anda</p>
                                <p style="margin:0;font-size:34px;font-weight:800;letter-spacing:0.28em;color:#1e3a8a;font-family:'JetBrains Mono',Consolas,monospace;">{{ $code }}</p>
                            </div>
                            <p style="margin:16px 0 0;font-size:12px;line-height:1.6;color:#94a3b8;">
                                Kode ini berlaku selama <strong style="color:#475569;">{{ $expiryMinutes }} menit</strong>. Jangan bagikan kode ini kepada siapa pun, termasuk pihak yang mengaku dari OmniSync.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 32px;">
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;">
                                Jika Anda tidak meminta kode ini, abaikan saja email ini. Tidak ada perubahan yang terjadi pada akun Anda.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 32px;">
                            <p style="margin:0;font-size:11px;color:#94a3b8;">
                                &copy; {{ date('Y') }} OmniSync Commerce Platform. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
