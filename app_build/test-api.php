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
} else {
    echo "❌ GAGAL: Login Customer gagal.\n\n";
}

echo "==================================================\n";
echo "             PENGUJIAN SELESAI                    \n";
echo "==================================================\n";
