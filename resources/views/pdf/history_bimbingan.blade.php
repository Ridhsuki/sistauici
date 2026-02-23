<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* Setup Halaman PDF */
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Header dengan Logo */
        .header-container {
            width: 100%;
            border-bottom: 3px double #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 80px;
        }

        .brand-section {
            text-align: right;
        }

        .brand-section h1 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-section p {
            margin: 2px 0;
            color: #7f8c8d;
            font-size: 10px;
        }

        /* Info Utama */
        .report-title {
            text-align: center;
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .report-title h2 {
            margin: 0;
            font-size: 14px;
            color: #2c3e50;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            width: 150px;
        }

        /* Summary Cards */
        .summary-wrapper {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
        }

        .summary-card span {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .summary-card strong {
            font-size: 16px;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #2c3e50;
        }

        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #e9ecef;
            border-left: 1px solid #f8f9fa;
            border-right: 1px solid #f8f9fa;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }

        .bg-success {
            background-color: #1a936f;
        }

        .bg-danger {
            background-color: #c94c4c;
        }

        .bg-warning {
            background-color: #f39c12;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            font-size: 9px;
            color: #95a5a6;
        }

        .text-muted {
            color: #7f8c8d;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td width="15%">
                    <img src="{{ public_path('favicon/android-chrome-512x512.png') }}" class="logo">
                </td>
                <td class="brand-section">
                    <h1>UNIVERSITAS INSAN CITA INDONESIA</h1>
                    <p>Sistem Informasi Tugas Akhir (SISTA)</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        <h2>{{ $title }}</h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td class="info-label">Nama {{ $role == 'dosen' ? 'Dosen' : 'Mahasiswa' }}</td>
                        <td width="10">:</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Email / ID</td>
                        <td>:</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Periode Laporan</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" style="text-align: center;">No</th>
                <th width="25%">{{ $role == 'dosen' ? 'Mahasiswa' : 'Dosen Pembimbing' }}</th>
                <th width="20%">Jadwal Sesi</th>
                <th width="12%" style="text-align: center;">Status</th>
                <th width="38%">Catatan / Evaluasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bimbingans as $index => $bimbingan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $role == 'dosen' ? $bimbingan->mahasiswa->name : $bimbingan->dosen->name }}</strong><br>
                        <span class="text-muted">ID:
                            {{ $role == 'dosen' ? $bimbingan->mahasiswa->NIM : $bimbingan->dosen->NIDN }}</span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d M Y') }}<br>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($bimbingan->waktu_selesai)->format('H:i') }} WIB</span>
                    </td>
                    <td style="text-align: center;">
                        @if ($bimbingan->status == 'approved')
                            <span class="badge bg-success">Selesai</span>
                        @elseif($bimbingan->status == 'rejected')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($bimbingan->status) }}</span>
                        @endif
                    </td>
                    <td style="font-style: italic; color: #555;">
                        {{ $bimbingan->catatan_dosen ?: 'Tidak ada catatan.' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Belum ada riwayat bimbingan yang
                        tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td>Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi Tugas Akhir UICI.</td>
                <td style="text-align: right;">Halaman 1 dari 1</td>
            </tr>
        </table>
    </div>

</body>

</html>
