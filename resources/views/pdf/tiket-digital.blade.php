<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $pesanan->booking_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f1a15;
            line-height: 1.5;
            font-size: 13px;
            margin: 0;
            padding: 30px;
            background-color: #f4f3ed;
        }
        .ticket-container {
            background-color: #ffffff;
            border: 1px solid #dfdfd6;
            border-radius: 20px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 2px solid #1e5e3a;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e5e3a;
            text-transform: uppercase;
            letter-spacing: 1px;
            float: left;
        }
        .title {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #3f4e45;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .clear {
            clear: both;
        }
        .booking-info {
            background-color: #f4f3ed;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .booking-code-label {
            font-size: 10px;
            color: #3f4e45;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .booking-code {
            font-size: 26px;
            font-weight: bold;
            color: #0f1a15;
            margin: 2px 0;
        }
        .status-badge {
            display: inline-block;
            background-color: #1e5e3a;
            color: #ffffff;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 20px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table td {
            padding: 10px 0;
            vertical-align: top;
            border-bottom: 1px solid #dfdfd6;
        }
        .label {
            font-size: 11px;
            color: #3f4e45;
            text-transform: uppercase;
            font-weight: bold;
            width: 35%;
        }
        .value {
            font-size: 13px;
            color: #0f1a15;
            font-weight: bold;
        }
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dfdfd6;
        }
        .qr-image {
            width: 150px;
            height: 150px;
            border: 1px solid #dfdfd6;
            padding: 10px;
            border-radius: 12px;
            background-color: #ffffff;
        }
        .qr-note {
            font-size: 11px;
            color: #3f4e45;
            margin-top: 10px;
            font-weight: bold;
        }
        .footer-note {
            text-align: center;
            font-size: 10px;
            color: #3f4e45;
            margin-top: 40px;
            border-top: 1px solid #dfdfd6;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="ticket-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Kelana</div>
            <div class="title">Official E-Ticket</div>
            <div class="clear"></div>
        </div>

        <!-- Booking Code & Status -->
        <div class="booking-info">
            <span class="booking-code-label">Booking Code</span>
            <div class="booking-code">{{ $pesanan->booking_code }}</div>
            <span class="status-badge">Paid & Confirmed</span>
        </div>

        <!-- Ticket Details -->
        <table class="details-table">
            <tr>
                <td class="label">Adventure Trip</td>
                <td class="value">{{ $pesanan->jadwal->paketWisata->nama_paket }}</td>
            </tr>
            <tr>
                <td class="label">Departure Date</td>
                <td class="value">{{ \Carbon\Carbon::parse($pesanan->jadwal->tanggal_mulai)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Trip Leader</td>
                <td class="value">{{ $pesanan->jadwal->tripLeader->nama_leader ?? 'Kelana Certified Leader' }}</td>
            </tr>
            <tr>
                <td class="label">Registered Pax</td>
                <td class="value">{{ $pesanan->jumlah_peserta }} Participants</td>
            </tr>
            <tr>
                <td class="label">Main Customer</td>
                <td class="value">{{ $pesanan->customer->nama_customer }}</td>
            </tr>
            @if($pesanan->total_biaya_addons > 0)
            <tr>
                <td class="label">Trip Add-ons</td>
                <td class="value">
                    @foreach($pesanan->addOns as $index => $addon)
                        {{ $addon->nama_addon }} (x{{ $addon->pivot->kuantitas }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
            </tr>
            @endif
            <tr>
                <td class="label">Package Cost</td>
                <td class="value">Rp {{ number_format($pesanan->jumlah_peserta * $pesanan->jadwal->paketWisata->harga, 0, ',', '.') }}</td>
            </tr>
            @if($pesanan->promo_code)
            <tr>
                <td class="label">Promo Discount ({{ $pesanan->promo_code }})</td>
                <td class="value" style="color: #c2410c;">-Rp {{ number_format($pesanan->diskon, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($pesanan->total_biaya_addons > 0)
            <tr>
                <td class="label">Add-ons Subtotal</td>
                <td class="value">Rp {{ number_format($pesanan->total_biaya_addons, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td class="label" style="color: #1e5e3a; font-weight: 800;">Total Payment</td>
                <td class="value" style="color: #1e5e3a; font-size: 15px; font-weight: 800;">Rp {{ number_format($pesanan->total_harga + $pesanan->total_biaya_addons, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- QR Code Check-in Section -->
        <div class="qr-section">
            <img class="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($pesanan->booking_code) }}" alt="QR Code Check-in">
            <div class="qr-note">Scan this QR Code at the meeting point for Check-in</div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            Thank you for traveling with Kelana. Please arrive at the meeting point 30 minutes before departure.
        </div>
    </div>

</body>
</html>
