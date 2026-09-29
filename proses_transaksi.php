<?php
// Cegah akses langsung lewat URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Halaman tidak bisa diakses. Silakan order melalui form BINGO.");
}

// 1. KONEKSI DATABASE
$host = 'localhost';
$dbname = 'db_ngetopup'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

// 2. TANGKAP DATA DARI FORM
$user_id = $_POST['user_id'] ?? '';
$zone_id = $_POST['zone_id'] ?? '';
$email = $_POST['email'] ?? '';
$whatsapp = $_POST['whatsapp'] ?? '';
$id_produk = $_POST['id_produk'] ?? '';

// 3. CARI DETAIL PRODUK 
$stmt = $pdo->prepare("SELECT nama_produk, harga_jual FROM produk WHERE id_produk = ?");
$stmt->execute([$id_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    die("Produk tidak ditemukan di database.");
}

// 4. BUAT VARIABEL UNTUK DATABASE & STRUK
$order_id = "INV-BINGO-" . time(); // Format disesuaikan dengan isi database
$tanggal_tampil = date('d-m-Y H:i');
$tanggal_db = date('Y-m-d H:i:s'); // Format waktu standar MySQL

// 5. MASUKKAN DATA KE TABEL TRANSAKSI
$insert = $pdo->prepare("INSERT INTO transaksi (invoice, user_id_game, zone_id_game, id_produk, metode_pembayaran, status_transaksi, tanggal_pesanan) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert->execute([
    $order_id, 
    $user_id, 
    $zone_id, 
    $id_produk, 
    'QRIS',      // Default metode pembayaran
    'Pending',   // Status awal pesanan
    $tanggal_db
]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pesanan - BINGO</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f7f6; padding: 20px; display: flex; justify-content: center; }
        .invoice-box { background: #fff; border: 4px solid #000; box-shadow: 5px 5px 0px #000; padding: 30px; width: 100%; max-width: 500px; }
        
        .title { font-size: 20px; font-weight: 900; margin-top: 0; text-align: center; background: #ffe600; padding: 15px; border: 4px solid #000; margin-bottom: 25px; box-shadow: 3px 3px 0px #000; }
        
        .detail-row { display: flex; justify-content: space-between; border-bottom: 2px dashed #000; padding: 12px 0; font-size: 15px; font-weight: bold; color: #333; }
        .detail-row span:last-child { color: #000; font-weight: 900; }
        
        .total-row { display: flex; justify-content: space-between; padding: 15px 0; font-size: 20px; font-weight: 900; color: #e74c3c; border-top: 4px solid #000; margin-top: 10px; }
        
        .btn-bayar { display: block; width: 100%; background: #e74c3c; color: #fff; text-align: center; padding: 15px; font-size: 18px; font-weight: 900; text-decoration: none; border: 4px solid #000; margin-top: 20px; box-sizing: border-box; transition: 0.1s; cursor: pointer; text-transform: uppercase; box-shadow: 4px 4px 0px #000; }
        .btn-bayar:hover { background: #c0392b; }
        .btn-bayar:active { transform: translate(3px, 3px); box-shadow: 1px 1px 0px #000; }
    </style>
</head>
<body>

    <div class="invoice-box">
        <h1 class="title">INVOICE TAGIHAN</h1>
        
        <div class="detail-row">
            <span>No. Order</span>
            <span><?= $order_id ?></span>
        </div>
        <div class="detail-row">
            <span>Tanggal</span>
            <span><?= $tanggal_tampil ?></span>
        </div>
        <div class="detail-row">
            <span>ID Akun</span>
            <span><?= htmlspecialchars($user_id) ?> (<?= htmlspecialchars($zone_id) ?>)</span>
        </div>
        <div class="detail-row">
            <span>Email</span>
            <span><?= htmlspecialchars($email) ?></span>
        </div>
        <div class="detail-row">
            <span>Item Pembelian</span>
            <span><?= htmlspecialchars($produk['nama_produk']) ?></span>
        </div>
        
        <div class="total-row">
            <span>TOTAL BAYAR</span>
            <span>Rp <?= number_format($produk['harga_jual'], 0, ',', '.') ?></span>
        </div>

        <button class="btn-bayar" onclick="alert('Pesanan berhasil dicatat! Menunggu integrasi Payment Gateway.')">LANJUTKAN PEMBAYARAN</button>
    </div>

</body>
</html>