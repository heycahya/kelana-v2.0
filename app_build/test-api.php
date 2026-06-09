<?php

$baseUrl = 'http://127.0.0.1:8000/api/v1';

function makeRequest($method, $url, $data = null, $token = null) {
    $options = [
        'http' => [
            'method' => $method,
            'header' => "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n",
            'ignore_errors' => true
        ]
    ];

    if ($token) {
        $options['http']['header'] .= "Authorization: Bearer $token\r\n";
    }

    if ($data) {
        $options['http']['content'] = json_encode($data);
    }

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    // Get HTTP status code
    $statusLine = $http_response_header[0] ?? '';
    preg_match('{HTTP\/\S*\s(\d{3})}', $statusLine, $match);
    $statusCode = $match[1] ?? 'Unknown';

    return [
        'status' => $statusCode,
        'body' => json_decode($response, true) ?? $response
    ];
}

echo "==================================================\n";
echo "       KELANA V2.0 - AUTOMATED API TESTER         \n";
echo "==================================================\n\n";

// 1. Login Admin
echo "[1] Melakukan Login Admin...\n";
$loginAdmin = makeRequest('POST', "$baseUrl/auth/login", [
    'email' => 'admin.kelana',
    'password' => 'PasswordAdmin123!'
]);

if ($loginAdmin['status'] != 200 || !isset($loginAdmin['body']['data']['token'])) {
    echo "❌ GAGAL: Login Admin gagal (HTTP {$loginAdmin['status']}).\n";
    print_r($loginAdmin['body']);
    exit(1);
}

$adminToken = $loginAdmin['body']['data']['token'];
echo "✅ BERHASIL: Login Admin sukses. Token didapatkan.\n\n";

// 2. Get All Paket
echo "[2] Mengambil Semua Paket Wisata (Admin Only)...\n";
$getAll = makeRequest('GET', "$baseUrl/admin/paket-wisata", null, $adminToken);
if ($getAll['status'] == 200) {
    echo "✅ BERHASIL (HTTP 200): Data diambil. Jumlah paket: " . count($getAll['body']['data']) . "\n\n";
} else {
    echo "❌ GAGAL (HTTP {$getAll['status']})\n";
    print_r($getAll['body']);
}

// 3. Create Paket Baru
echo "[3] Menambahkan Paket Wisata Baru...\n";
$newPaket = [
    'nama_paket' => 'Trip Pantai Tiga Warna',
    'deskripsi' => 'Open trip ke pantai terindah di Malang.',
    'harga' => 150000,
    'rute' => 'Malang - Tiga Warna - Malang',
    'fasilitas' => 'Snorkeling, Tiket, Guide'
];
$create = makeRequest('POST', "$baseUrl/admin/paket-wisata", $newPaket, $adminToken);
if ($create['status'] == 201) {
    $createdId = $create['body']['data']['id_paket'];
    echo "✅ BERHASIL (HTTP 201): Paket ditambahkan dengan ID $createdId\n\n";
} else {
    echo "❌ GAGAL (HTTP {$create['status']})\n";
    print_r($create['body']);
    exit(1);
}

// 4. Detail Paket
echo "[4] Melihat Detail Paket Wisata ID $createdId...\n";
$detail = makeRequest('GET', "$baseUrl/admin/paket-wisata/$createdId", null, $adminToken);
if ($detail['status'] == 200) {
    echo "✅ BERHASIL (HTTP 200): Nama Paket = " . $detail['body']['data']['nama_paket'] . "\n\n";
} else {
    echo "❌ GAGAL (HTTP {$detail['status']})\n";
}

// 5. Update Paket
echo "[5] Mengupdate Paket Wisata ID $createdId...\n";
$updatePaket = $newPaket;
$updatePaket['nama_paket'] = 'Trip Pantai Tiga Warna (Updated)';
$update = makeRequest('PUT', "$baseUrl/admin/paket-wisata/$createdId", $updatePaket, $adminToken);
if ($update['status'] == 200) {
    echo "✅ BERHASIL (HTTP 200): Nama terupdate = " . $update['body']['data']['nama_paket'] . "\n\n";
} else {
    echo "❌ GAGAL (HTTP {$update['status']})\n";
}

// 6. Delete Paket
echo "[6] Menghapus Paket Wisata ID $createdId...\n";
$delete = makeRequest('DELETE', "$baseUrl/admin/paket-wisata/$createdId", null, $adminToken);
if ($delete['status'] == 200) {
    echo "✅ BERHASIL (HTTP 200): Paket berhasil dihapus.\n\n";
} else {
    echo "❌ GAGAL (HTTP {$delete['status']})\n";
}

// 7. Login Customer untuk test proteksi
echo "[7] Login sebagai Customer untuk menguji proteksi rute...\n";
$loginCustomer = makeRequest('POST', "$baseUrl/auth/login", [
    'email' => 'budi.santoso@kelana.com',
    'password' => 'PasswordCustomer123!'
]);

if ($loginCustomer['status'] == 200 && isset($loginCustomer['body']['data']['token'])) {
    $customerToken = $loginCustomer['body']['data']['token'];
    echo "✅ BERHASIL: Login Customer sukses.\n";
    
    echo "-> Mencoba mengakses rute admin menggunakan token Customer...\n";
    $testAccess = makeRequest('GET', "$baseUrl/admin/paket-wisata", null, $customerToken);
    
    if ($testAccess['status'] == 403) {
        echo "✅ BERHASIL (HTTP 403 Forbidden): Rute terproteksi dengan benar!\n";
        echo "Response Message: " . $testAccess['body']['message'] . "\n\n";
    } else {
        echo "❌ GAGAL: Rute dapat ditembus (HTTP {$testAccess['status']}).\n\n";
    }

    // ==========================================
    // JADWAL TRIP TESTING
    // ==========================================
    echo "--------------------------------------------------\n";
    echo "       PENGUJIAN CRUD JADWAL TRIP (ADMIN)         \n";
    echo "--------------------------------------------------\n\n";

    // 8. Get All Jadwal Trip
    echo "[8] Mengambil Semua Jadwal Trip...\n";
    $getJadwalAll = makeRequest('GET', "$baseUrl/admin/jadwal-trip", null, $adminToken);
    if ($getJadwalAll['status'] == 200) {
        echo "✅ BERHASIL (HTTP 200): Data diambil. Jumlah jadwal: " . count($getJadwalAll['body']['data']) . "\n\n";
    } else {
        echo "❌ GAGAL (HTTP {$getJadwalAll['status']})\n";
        print_r($getJadwalAll['body']);
    }

    // 9. Create Jadwal Trip Baru (Valid)
    echo "[9] Menambahkan Jadwal Trip Baru (Valid)...\n";
    $newJadwal = [
        'id_paket' => 1,
        'id_leader' => 1,
        'tanggal_mulai' => '2026-09-01',
        'tanggal_selesai' => '2026-09-05',
        'kuota' => 10,
        'status_trip' => 'Open'
    ];
    $createJadwal = makeRequest('POST', "$baseUrl/admin/jadwal-trip", $newJadwal, $adminToken);
    if ($createJadwal['status'] == 201) {
        $createdJadwalId = $createJadwal['body']['data']['id_jadwal'];
        echo "✅ BERHASIL (HTTP 201): Jadwal ditambahkan dengan ID $createdJadwalId\n\n";
    } else {
        echo "❌ GAGAL (HTTP {$createJadwal['status']})\n";
        print_r($createJadwal['body']);
        exit(1);
    }

    // 10. Create Jadwal Trip Baru (Invalid Validation)
    echo "[10] Menambahkan Jadwal Trip Baru (Gagal Validasi - tanggal_selesai sebelum tanggal_mulai & status_trip salah)...\n";
    $invalidJadwal = [
        'id_paket' => 1,
        'id_leader' => 1,
        'tanggal_mulai' => '2026-09-05',
        'tanggal_selesai' => '2026-09-01',
        'kuota' => 0,
        'status_trip' => 'InvalidStatus'
    ];
    $createInvalid = makeRequest('POST', "$baseUrl/admin/jadwal-trip", $invalidJadwal, $adminToken);
    if ($createInvalid['status'] == 422) {
        echo "✅ BERHASIL (HTTP 422 Unprocessable Entity): Validasi berhasil menolak data tidak valid.\n";
        echo "Response Errors: " . json_encode($createInvalid['body']['errors']) . "\n\n";
    } else {
        echo "❌ GAGAL: Validasi lolos dengan status {$createInvalid['status']}\n\n";
    }

    // 11. Detail Jadwal Trip
    echo "[11] Melihat Detail Jadwal Trip ID $createdJadwalId...\n";
    $detailJadwal = makeRequest('GET', "$baseUrl/admin/jadwal-trip/$createdJadwalId", null, $adminToken);
    if ($detailJadwal['status'] == 200) {
        echo "✅ BERHASIL (HTTP 200): Paket Wisata = " . ($detailJadwal['body']['data']['paket_wisata']['nama_paket'] ?? 'N/A') . "\n";
        echo "Trip Leader = " . ($detailJadwal['body']['data']['trip_leader']['nama_leader'] ?? 'N/A') . "\n\n";
    } else {
        echo "❌ GAGAL (HTTP {$detailJadwal['status']})\n";
    }

    // 12. Update Jadwal Trip
    echo "[12] Mengupdate Jadwal Trip ID $createdJadwalId (Ubah kuota & status)...\n";
    $updateJadwalData = $newJadwal;
    $updateJadwalData['kuota'] = 25;
    $updateJadwalData['status_trip'] = 'Berjalan';
    $updateJadwal = makeRequest('PUT', "$baseUrl/admin/jadwal-trip/$createdJadwalId", $updateJadwalData, $adminToken);
    if ($updateJadwal['status'] == 200) {
        echo "✅ BERHASIL (HTTP 200): Kuota terupdate = " . $updateJadwal['body']['data']['kuota'] . ", Status terupdate = " . $updateJadwal['body']['data']['status_trip'] . "\n\n";
    } else {
        echo "❌ GAGAL (HTTP {$updateJadwal['status']})\n";
    }

    // 13. Delete Jadwal Trip
    echo "[13] Menghapus Jadwal Trip ID $createdJadwalId...\n";
    $deleteJadwal = makeRequest('DELETE', "$baseUrl/admin/jadwal-trip/$createdJadwalId", null, $adminToken);
    if ($deleteJadwal['status'] == 200) {
        echo "✅ BERHASIL (HTTP 200): Jadwal berhasil dihapus.\n\n";
    } else {
        echo "❌ GAGAL (HTTP {$deleteJadwal['status']})\n";
    }

    // 14. Test Access protection Jadwal Trip using Customer token
    if (isset($customerToken)) {
        echo "[14] Mencoba mengakses rute Jadwal Trip admin menggunakan token Customer...\n";
        $testAccessJadwal = makeRequest('GET', "$baseUrl/admin/jadwal-trip", null, $customerToken);
        if ($testAccessJadwal['status'] == 403) {
            echo "✅ BERHASIL (HTTP 403 Forbidden): Rute Jadwal Trip terproteksi dengan benar!\n\n";
        } else {
            echo "❌ GAGAL: Rute Jadwal Trip dapat ditembus oleh Customer (HTTP {$testAccessJadwal['status']}).\n\n";
        }
    }

    // ==========================================
    // CUSTOMER BOOKING & MIDTRANS TESTING
    // ==========================================
    echo "--------------------------------------------------\n";
    echo "       PENGUJIAN API PEMESANAN & INTEGRASI        \n";
    echo "--------------------------------------------------\n\n";

    // 15. Create Pemesanan (Valid)
    echo "[15] Membuat Pemesanan Baru (Valid - 2 peserta)...\n";
    $bookingData = [
        'id_jadwal' => 1,
        'jumlah_peserta' => 2
    ];
    $createBooking = makeRequest('POST', "$baseUrl/pemesanan", $bookingData, $customerToken);
    $bookingCode = null;
    if ($createBooking['status'] == 201) {
        $bookingCode = $createBooking['body']['data']['booking_code'];
        echo "✅ BERHASIL (HTTP 201 Created):\n";
        echo "   Booking Code: " . $createBooking['body']['data']['booking_code'] . "\n";
        echo "   Total Harga : " . $createBooking['body']['data']['total_harga'] . "\n";
        echo "   Snap Token  : " . $createBooking['body']['data']['snap_token'] . "\n\n";
    } else {
        echo "❌ GAGAL (HTTP {$createBooking['status']})\n";
        print_r($createBooking['body']);
        exit(1);
    }

    // 16. Create Pemesanan (Gagal Validasi)
    echo "[16] Membuat Pemesanan Baru (Gagal Validasi - jumlah_peserta = 0)...\n";
    $invalidBookingData = [
        'id_jadwal' => 1,
        'jumlah_peserta' => 0
    ];
    $createInvalidBooking = makeRequest('POST', "$baseUrl/pemesanan", $invalidBookingData, $customerToken);
    if ($createInvalidBooking['status'] == 422) {
        echo "✅ BERHASIL (HTTP 422 Unprocessable Entity): Validasi berhasil menolak jumlah peserta kurang dari 1.\n";
        echo "   Response Errors: " . json_encode($createInvalidBooking['body']['errors']) . "\n\n";
    } else {
        echo "❌ GAGAL: Validasi lolos dengan status {$createInvalidBooking['status']}\n\n";
    }

    // 17. Create Pemesanan (Kuota Tidak Cukup)
    echo "[17] Membuat Pemesanan Baru (Gagal - kuota tidak cukup/999 peserta)...\n";
    $overQuotaBookingData = [
        'id_jadwal' => 1,
        'jumlah_peserta' => 999
    ];
    $createOverQuota = makeRequest('POST', "$baseUrl/pemesanan", $overQuotaBookingData, $customerToken);
    if ($createOverQuota['status'] == 422) {
        echo "✅ BERHASIL (HTTP 422 Unprocessable Entity): Sistem berhasil menolak karena kuota tidak mencukupi.\n";
        echo "   Message: " . $createOverQuota['body']['message'] . "\n\n";
    } else {
        echo "❌ GAGAL: Sistem membiarkan kuota berlebih lolos dengan status {$createOverQuota['status']}\n\n";
    }

    // 18. Test Admin cannot access Pemesanan endpoint
    echo "[18] Mencoba mengakses rute Pemesanan Customer menggunakan token Admin...\n";
    $testAdminAccessBooking = makeRequest('POST', "$baseUrl/pemesanan", $bookingData, $adminToken);
    if ($testAdminAccessBooking['status'] == 403) {
        echo "✅ BERHASIL (HTTP 403 Forbidden): Rute Pemesanan terproteksi dari Admin dengan benar!\n\n";
    } else {
        echo "❌ GAGAL: Rute Pemesanan dapat diakses oleh Admin (HTTP {$testAdminAccessBooking['status']}).\n\n";
    }

    // ==========================================
    // MIDTRANS WEBHOOK TESTING
    // ==========================================
    echo "--------------------------------------------------\n";
    echo "       PENGUJIAN WEBHOOK INTEGRASI MIDTRANS        \n";
    echo "--------------------------------------------------\n\n";

    // 19. Webhook: Order Not Found (404)
    echo "[19] Menguji Webhook Midtrans - Booking Code Tidak Ditemukan...\n";
    $webhookNotFound = makeRequest('POST', "$baseUrl/webhook/midtrans", [
        'order_id' => 'TRIP-NOT-FOUND-999',
        'transaction_status' => 'settlement',
        'payment_type' => 'bank_transfer',
        'transaction_id' => 'trx-mock-999',
        'transaction_time' => '2026-06-09 23:20:00',
        'gross_amount' => '100000'
    ]);
    if ($webhookNotFound['status'] == 404) {
        echo "✅ BERHASIL (HTTP 404 Not Found): Webhook mengembalikan 404 untuk order_id yang salah.\n\n";
    } else {
        echo "❌ GAGAL: Webhook meloloskan order_id tidak valid (HTTP {$webhookNotFound['status']}).\n\n";
    }

    if ($bookingCode) {
        // 20. Webhook: Status Pending
        echo "[20] Menguji Webhook Midtrans - Status PENDING...\n";
        $webhookPending = makeRequest('POST', "$baseUrl/webhook/midtrans", [
            'order_id' => $bookingCode,
            'transaction_status' => 'pending',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'trx-mock-pending',
            'transaction_time' => '2026-06-09 23:21:00',
            'gross_amount' => $createBooking['body']['data']['total_harga']
        ]);
        if ($webhookPending['status'] == 200) {
            echo "✅ BERHASIL (HTTP 200 OK): Status PENDING diproses.\n\n";
        } else {
            echo "❌ GAGAL: Webhook pending gagal diproses (HTTP {$webhookPending['status']}).\n\n";
        }

        // 21. Webhook: Status Settlement (SUCCESS)
        echo "[21] Menguji Webhook Midtrans - Status SETTLEMENT (Sukses)...\n";
        $webhookSettlement = makeRequest('POST', "$baseUrl/webhook/midtrans", [
            'order_id' => $bookingCode,
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'trx-mock-settled',
            'transaction_time' => '2026-06-09 23:22:00',
            'gross_amount' => $createBooking['body']['data']['total_harga']
        ]);
        if ($webhookSettlement['status'] == 200) {
            echo "✅ BERHASIL (HTTP 200 OK): Status SETTLEMENT diproses.\n\n";
        } else {
            echo "❌ GAGAL: Webhook settlement gagal diproses (HTTP {$webhookSettlement['status']}).\n\n";
        }

        // 22. Webhook: Status Expire (Quota Restored)
        echo "[22] Membuat Booking Baru untuk Pengujian Expire & Pengembalian Kuota...\n";
        
        // Cek kuota sebelum booking baru
        $checkJadwalBefore = makeRequest('GET', "$baseUrl/admin/jadwal-trip/1", null, $adminToken);
        $kuotaBefore = $checkJadwalBefore['body']['data']['sisa_kuota'] ?? 0;
        echo "     Kuota awal jadwal ID 1: $kuotaBefore\n";

        $tempBooking = makeRequest('POST', "$baseUrl/pemesanan", [
            'id_jadwal' => 1,
            'jumlah_peserta' => 3
        ], $customerToken);

        if ($tempBooking['status'] == 201) {
            $tempBookingCode = $tempBooking['body']['data']['booking_code'];
            
            // Cek kuota sesudah booking baru (harus berkurang 3)
            $checkJadwalAfterBooking = makeRequest('GET', "$baseUrl/admin/jadwal-trip/1", null, $adminToken);
            $kuotaAfterBooking = $checkJadwalAfterBooking['body']['data']['sisa_kuota'] ?? 0;
            echo "     Kuota setelah booking (dikurangi 3): $kuotaAfterBooking\n";

            // Kirim webhook EXPIRE
            echo "     Mengirim Webhook Midtrans EXPIRE untuk booking $tempBookingCode...\n";
            $webhookExpire = makeRequest('POST', "$baseUrl/webhook/midtrans", [
                'order_id' => $tempBookingCode,
                'transaction_status' => 'expire',
                'payment_type' => 'bank_transfer',
                'transaction_id' => 'trx-mock-expired',
                'transaction_time' => '2026-06-09 23:25:00',
                'gross_amount' => $tempBooking['body']['data']['total_harga']
            ]);

            if ($webhookExpire['status'] == 200) {
                // Cek kuota sesudah expire (harus kembali bertambah 3)
                $checkJadwalAfterExpire = makeRequest('GET', "$baseUrl/admin/jadwal-trip/1", null, $adminToken);
                $kuotaAfterExpire = $checkJadwalAfterExpire['body']['data']['sisa_kuota'] ?? 0;
                echo "     Kuota setelah webhook EXPIRE (harus kembali bertambah 3): $kuotaAfterExpire\n";

                if ($kuotaAfterExpire == $kuotaBefore) {
                    echo "✅ BERHASIL (HTTP 200 OK): Status EXPIRE diproses dan kuota berhasil dikembalikan!\n\n";
                } else {
                    echo "❌ GAGAL: Kuota tidak kembali ke nilai awal ($kuotaBefore) setelah EXPIRE (kuota sekarang: $kuotaAfterExpire).\n\n";
                }
            } else {
                echo "❌ GAGAL: Webhook expire gagal diproses (HTTP {$webhookExpire['status']}).\n\n";
            }
        } else {
            echo "❌ GAGAL: Gagal membuat booking sementara untuk tes expire.\n\n";
        }
    } else {
        echo "⚠️ KEPUTUSAN: Booking code tidak ada, pengujian webhook dilewati.\n\n";
    }
} else {
    echo "❌ GAGAL: Login Customer gagal.\n\n";
}

echo "==================================================\n";
echo "             PENGUJIAN SELESAI                    \n";
echo "==================================================\n";
