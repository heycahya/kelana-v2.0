<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trip Participant Recap Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.4;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #0056b3;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666666;
            font-size: 11px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 6px 10px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #555555;
            width: 30%;
        }
        .info-value {
            color: #111111;
        }
        .stats-container {
            margin-bottom: 25px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-table td {
            text-align: center;
            width: 50%;
            padding: 5px;
        }
        .stats-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .stats-value.blue {
            color: #0056b3;
        }
        .table-title {
            font-size: 14px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 10px;
            border-left: 3px solid #0056b3;
            padding-left: 8px;
        }
        .manifest-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .manifest-table th {
            background-color: #0056b3;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .manifest-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        .manifest-table tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-hadir {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-belum {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
            color: #888888;
        }
        .signature-area {
            margin-top: 50px;
            float: right;
            width: 200px;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333333;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Trip Participant Recap Report</h1>
        <p>Kelana Travel Digital System • Printed on: {{ date('d-M-Y H:i') }}</p>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-label">Travel Package</td>
            <td class="info-value">: <strong>{{ $jadwal->paketWisata->nama_paket ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Start Date</td>
            <td class="info-value">: {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">End Date</td>
            <td class="info-value">: {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Trip Leader</td>
            <td class="info-value">: {{ $jadwal->tripLeader->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Trip Status</td>
            <td class="info-value">: {{ $jadwal->status_trip }}</td>
        </tr>
    </table>

    <div class="stats-container">
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stats-label">Total Paid Participants</div>
                    <div class="stats-value blue">{{ $totalPeserta }} People</div>
                </td>
                <td>
                    <div class="stats-label">Total Revenue</div>
                    <div class="stats-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="table-title">Participant Manifest List (PAID)</div>
    <table class="manifest-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Booking Code</th>
                <th style="width: 30%;">Customer Name</th>
                <th style="width: 15%; text-align: center;">Total Participants</th>
                <th style="width: 15%; text-align: center;">Total Present</th>
                <th style="width: 20%; text-align: center;">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><code>{{ $item->booking_code }}</code></td>
                    <td>{{ $item->customer->nama ?? '-' }}</td>
                    <td style="text-align: center;">{{ $item->jumlah_peserta }}</td>
                    <td style="text-align: center;">{{ $item->jumlah_hadir }}</td>
                    <td style="text-align: center;">
                        @if($item->attendance_status === 'hadir')
                            <span class="status-badge status-hadir">Present</span>
                        @else
                            <span class="status-badge status-belum">Absent</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #777777; padding: 15px;">No paid participants registered for this trip schedule.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-area">
            <p>Operations Admin,</p>
            <div class="signature-line">Kelana Back-Office</div>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
