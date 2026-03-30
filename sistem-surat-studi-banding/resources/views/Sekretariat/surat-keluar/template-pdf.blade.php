<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Balasan Studi Banding</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0d1b2a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 13px;
        }

        .content {
            margin: 30px 0;
            text-align: justify;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 45%;
            text-align: center;
        }

        .signature-image {
            height: 80px;
            margin: 10px 0;
        }

        .signature-image img {
            max-height: 80px;
            max-width: 150px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 30px;
        }

        .date {
            margin-bottom: 20px;
        }

        .meta {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SURAT BALASAN</h2>
        <p>Permohonan Studi Banding</p>
    </div>

    <div class="date">
        <strong>Tanggal:</strong> {{ $tanggalPDF }}
    </div>

    <div>
        <p><strong>Ref. Surat Masuk:</strong> {{ $suratMasuk->no_surat }}</p>
        <p><strong>Dari:</strong> {{ $suratMasuk->instansi }}</p>
        <p><strong>Perihal:</strong> {{ $suratMasuk->perihal }}</p>
    </div>

    <div class="content">
        {!! nl2br(e($isiSurat)) !!}
    </div>

    <div class="signature-section">
        <div class="signature-block">
            <div class="date">{{ $tanggalPDF }}</div>
        </div>
        <div class="signature-block">
            @if ($tandaTanganPath)
                <div class="signature-image">
                    <img src="{{ $tandaTanganPath }}" alt="Tanda Tangan Digital">
                </div>
            @endif
            <div class="signature-name">{{ $namaPimpinan }}</div>
        </div>
    </div>

    <div class="meta">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Manajemen Surat Studi Banding pada {{ $tanggalPDF }}.</p>
        <p>ID Disposisi: {{ $disposisi->id }} | Status: {{ $disposisi->status_keputusan }}</p>
    </div>
</body>

</html>
