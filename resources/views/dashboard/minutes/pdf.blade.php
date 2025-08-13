<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ public_path('img/application-logo.svg') }}" type="image/x-icon">
    <title>Notulen</title>
    <style>
        /* Atur ukuran kertas dan margin */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .kop {
            overflow: hidden;
        }

        .kop img {
            float: left;
            width: 90px;
            height: 80px;
            margin-right: 20px;
            margin-bottom: 20px
        }

        .kop-text {
            overflow: hidden;
        }

        .kop-text h1 {
            text-transform: uppercase;
            font-size: 14px;
            margin: 0;
            text-align: left;
        }

        .kop-text p {
            margin: 2px 0;
        }

        hr {
            border: 1px solid black;
            margin: 15px 0;
        }

        h1,
        h2 {
            font-size: 14px;
            font-weight: bold;
            text-align: justify;
            margin: 0;
        }

        p {
            font-size: 12px;
            margin: 0;
            text-align: justify;
        }

        .section {
            margin-bottom: 15px;
        }

        /* Footer untuk nomor halaman (opsional) */
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Bagian Kop Laporan -->
    <div class="kop">
        <img src="img/application-logo.svg" alt="Kop Surat">
        <div class="kop-text">
            <h1>Badan Pusat Statistik</h1>
            <p>Jl. 17 Agustus No.7, Teling Atas, Kec. Wanea, Kota Manado, Sulawesi Utara</p>
            <p>Telepon: (0431) 847044 | Email: bps7100@bps.go.id</p>
            <p>Website: https://sulut.bps.go.id/id</p>
        </div>
    </div>
    <hr>

    <!-- Bagian Atas -->
    <div class="section">
        <h1>Masalah</h1>
        <p>{!! $minute->problem !!}</p>

        <br>

        <h1>Solusi</h1>
        <p>{!! $minute->solution !!}</p>
    </div>

    <!-- Bagian Tengah -->
    <div class="section">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%" valign="top">
                    <h2>Ditindak Lanjuti Oleh</h2>
                    {!! $minute->user->name !!}<br>
                </td>
                <td width="50%" valign="top">
                    <h2>Batas Tindak Lanjut</h2>
                    {{ $minute->follow_up_limits }}<br>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bagian Bawah -->
    <div class="section">
        <h2>Rencana Tindak Lanjut</h2>
        <p>{!! $minute->follow_up_plan !!}</p>

        <br>

        <h2>Sumber Data</h2>
        <p>{!! $minute->data_source !!}</p>
    </div>

    <!-- Tempat Tanda Tangan -->
    <div style="margin-top: 200px; text-align: right;">
        <p>Manado, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
        <p style="margin-bottom: 70px;">Kepala BPS,</p>
        <p><strong>......</strong></p>
    </div>
</body>

</html>
