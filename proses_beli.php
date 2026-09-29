<?php
include 'koneksi.php';

// 1. Menangkap data dari form
$user_id = $_POST['user_id'];
$zone_id = $_POST['zone_id'];
$id_produk = $_POST['id_produk'];
$email = $_POST['email'];
$promo = $_POST['promo']; // Ditangkap untuk kebutuhan sistem diskon nantinya

// Cek apakah No HP diisi, jika kosong beri nilai default agar Midtrans tidak error
$phone = !empty($_POST['phone']) ? $_POST['phone'] : '-'; 

$payment = 'Midtrans'; 

// 2. Ambil detail produk asli dari database
$query_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
$data_produk = mysqli_fetch_array($query_produk);

$harga = $data_produk['harga_jual'];
$nama_produk = $data_produk['nama_produk'];

$invoice = "INV-BINGO-" . time();

// ==========================================
// KONFIGURASI MIDTRANS SANDBOX
// ==========================================
$serverKey = 'SB-Mid-server-OBOkMy3NoMe7D28E-eHNcaY0'; 

$transaction_details = [
    'order_id' => $invoice,
    'gross_amount' => (int) $harga
];

$item_details = [
    [
        'id' => $id_produk,
        'price' => (int) $harga,
        'quantity' => 1,
        'name' => 'Top Up ' . $nama_produk
    ]
];

// Data pelanggan asli disuntikkan ke sini
$customer_details = [
    'first_name' => 'ID: ' . $user_id,
    'last_name' => '(' . $zone_id . ')',
    'email' => $email,
    'phone' => $phone
];

$payload = [
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details
];

// ==========================================
// PROSES NGE-TEMBAK API MIDTRANS (CURL)
// ==========================================
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://app.sandbox.midtrans.com/snap/v1/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_USERPWD, $serverKey . ':');

$headers = [
    'Accept: application/json',
    'Content-Type: application/json'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("Koneksi API Midtrans Error: " . $err);
}

$result = json_decode($response, true);

if (isset($result['redirect_url'])) {
    $url_pembayaran = $result['redirect_url'];

    // Simpan data transaksi ke database lokal
    $sql = "INSERT INTO transaksi (invoice, user_id_game, zone_id_game, id_produk, metode_pembayaran, status_transaksi) 
            VALUES ('$invoice', '$user_id', '$zone_id', '$id_produk', '$payment', 'Pending')";
    mysqli_query($koneksi, $sql);

    header("Location: " . $url_pembayaran);
    exit();
} else {
    echo "Gagal membuat tagihan Midtrans. Cek Error di bawah ini: <br>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}
?>