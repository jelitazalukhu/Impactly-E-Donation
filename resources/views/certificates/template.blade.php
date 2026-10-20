<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Donasi - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 10mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #F8F7F3;
            color: #26384D;
        }
        .cert-container {
            border: 4px double #334F70;
            padding: 4mm;
            background: #FFFFFF;
            height: 180mm;
        }
        .cert-inner {
            border: 1.5px solid #DCE3EA;
            padding: 20px 30px;
            text-align: center;
            background: #FFFFFF;
            height: 168mm;
        }
        .cert-header {
            margin-bottom: 12px;
        }
        .logo-text {
            font-size: 16px;
            font-weight: 800;
            color: #334F70;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .cert-title {
            font-size: 32px;
            font-weight: 800;
            color: #26384D;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin: 8px 0 4px;
        }
        .cert-subtitle {
            font-size: 12px;
            color: #718096;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .cert-number {
            font-size: 11px;
            color: #8AA2BA;
            margin-top: 4px;
            font-weight: 600;
        }
        .cert-body {
            margin: 18px 0 14px;
        }
        .presented-to {
            font-size: 13px;
            color: #718096;
            font-style: italic;
            margin-bottom: 6px;
        }
        .donor-name {
            font-size: 28px;
            font-weight: 800;
            color: #334F70;
            border-bottom: 2px solid #E8EEF4;
            display: inline-block;
            padding: 0 45px 5px;
            margin-bottom: 10px;
        }
        .cert-text {
            font-size: 13.5px;
            line-height: 1.55;
            color: #26384D;
            max-width: 680px;
            margin: 0 auto 10px;
        }
        .campaign-name {
            font-weight: 700;
            color: #334F70;
            font-size: 15.5px;
        }
        .amount-box {
            display: inline-block;
            background: #E8EEF4;
            border: 1px solid #DCE3EA;
            padding: 7px 26px;
            border-radius: 20px;
            margin-top: 6px;
        }
        .amount-text {
            font-size: 17px;
            font-weight: 800;
            color: #334F70;
        }
        .cert-footer {
            margin-top: 22px;
            width: 100%;
        }
        .cert-footer table {
            width: 100%;
            border-collapse: collapse;
        }
        .sign-col {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            font-size: 11.5px;
        }
        .sign-title {
            color: #718096;
            margin-bottom: 38px;
            font-size: 11px;
        }
        .sign-name {
            font-weight: 700;
            color: #26384D;
            border-top: 1px solid #DCE3EA;
            padding-top: 4px;
            display: inline-block;
            min-width: 160px;
        }
        .stamp-badge {
            display: inline-block;
            border: 2px dashed #334F70;
            color: #334F70;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.35;
        }
    </style>
</head>
<body>

<div class="cert-container">
    <div class="cert-inner">
        <div class="cert-header">
            <div class="logo-text">&#9829; IMPACTLY PLATFORM</div>
            <div class="cert-title">SERTIFIKAT APRESIASI</div>
            <div class="cert-subtitle">Penghargaan atas Kebaikan & Kepedulian Sosial</div>
            <div class="cert-number">Nomor Sertifikat: {{ $certificate->certificate_number }}</div>
        </div>

        <div class="cert-body">
            <div class="presented-to">Sertifikat ini dengan tulus kami anugerahkan kepada:</div>
            <div class="donor-name">{{ $certificate->donation->displayName() }}</div>
            
            <div class="cert-text">
                Atas keikutsertaan dan kontribusi nyata dalam mendukung penggalangan dana kemanusiaan:
                <br>
                <span class="campaign-name">&ldquo;{{ $certificate->donation->campaign->title }}&rdquo;</span>
            </div>

            <div class="amount-box">
                <span class="amount-text">{{ \App\Support\Rupiah::format($certificate->donation->amount) }}</span>
            </div>
        </div>

        <div class="cert-footer">
            <table>
                <tr>
                    <td class="sign-col">
                        <div class="sign-title">Penggalang Dana</div>
                        <div class="sign-name">{{ $certificate->donation->campaign->owner->organization_name ?? $certificate->donation->campaign->owner->name ?? 'Penggalang Impactly' }}</div>
                    </td>
                    <td class="sign-col">
                        <div class="stamp-badge">
                            TERVERIFIKASI RESMI<br>
                            IMPACTLY DIGITAL
                        </div>
                    </td>
                    <td class="sign-col">
                        <div class="sign-title">Diterbitkan pada {{ $certificate->issued_at ? $certificate->issued_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</div>
                        <div class="sign-name">Platform Impactly</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>
