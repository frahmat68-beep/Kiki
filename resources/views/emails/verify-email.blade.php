<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText }}</title>
</head>
<body style="margin:0;padding:0;background:#eef3ff;font-family:'Plus Jakarta Sans','Segoe UI',Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3ff;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;background:#ffffff;border:1px solid #dbe5ff;border-radius:18px;overflow:hidden;">
                    <tr>
                        <td style="padding:20px 24px;background:linear-gradient(135deg,#0f2f8f,#3159d8);color:#ffffff;">
                            <p style="margin:0;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;font-weight:700;opacity:0.9;">Manake</p>
                            <h1 style="margin:10px 0 6px;font-size:22px;line-height:1.35;font-weight:800;color:#ffffff;">{{ $subjectText }}</h1>
                            <p style="margin:0;font-size:14px;line-height:1.5;opacity:0.95;">{{ $subtitleText }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 10px;font-size:16px;font-weight:700;color:#1e3a8a;">{{ $headingText }}</p>
                            <p style="margin:0 0 16px;font-size:14px;line-height:1.7;color:#334155;">{{ $introText }}</p>

                            <div style="margin:18px 0 20px;">
                                <a
                                    href="{{ $verificationUrl }}"
                                    style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:11px 18px;border-radius:10px;font-size:14px;font-weight:700;"
                                >
                                    {{ $buttonText }}
                                </a>
                            </div>

                            <p style="margin:0 0 8px;font-size:13px;line-height:1.6;color:#475569;">{{ $expireText }}</p>
                            <p style="margin:0 0 12px;font-size:13px;line-height:1.6;color:#475569;">{{ $ignoreText }}</p>

                            <div style="margin-top:14px;padding:10px 12px;border-radius:10px;background:#eff6ff;border:1px solid #dbeafe;">
                                <p style="margin:0;font-size:12px;line-height:1.6;color:#1e40af;">
                                    {{ $noReplyText }}
                                </p>
                            </div>

                            <p style="margin:16px 0 0;font-size:12px;line-height:1.6;color:#64748b;word-break:break-all;">
                                {{ $verificationUrl }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
