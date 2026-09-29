<?php
$api_url = 'https://vip-reseller.co.id/api/game-feature'; 

// Ganti pakai data asli lu dari dashboard VIP Reseller!
$api_key = 'xzfFCOypGCTB0SXNdAhNvqwaigWDKjv9HikXS2HHOvh3Mls2r08v3nahr1Qf2yXS';
$api_id  = '6hIX8fup'; 

$data = [
    'key'    => $api_key,
    'sign'   => md5($api_id . $api_key), 
    'type'   => 'services',
    
    // Kita tambahkan filter langsung dari server VIP (biar tarikannya lebih enteng)
    'filter_type' => 'game',
    'filter_value' => 'Mobile Legends'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("Koneksi ke VIP Reseller Error: " . $err);
}

$result = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Harga Modal MLBB - BingO</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #ffd700; color: #000; font-weight: bold; }
        .cuan { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Gudang VIP: Daftar Harga Modal Mobile Legends</h2>
    <p>Ini adalah daftar harga asli dari server pusat VIP Reseller. Pilih beberapa produk untuk di-input ke database phpMyAdmin BingO.</p>

    <table>
        <thead>
            <tr>
                <th>Kode Produk (API)</th>
                <th>Nama Nominal</th>
                <th>Harga Modal (Basic)</th>
                <th>Status</th>
                <th>Saran Harga Jual BingO (+ Rp 2.500)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Memastikan data sukses ditarik
            if ($result['result'] == true) {
                // Loop: Mencetak isi array ke dalam baris tabel
                foreach ($result['data'] as $item) {
                    
                    // Filter ekstra: Pastikan cuma MLBB yang muncul (kadang API pusat suka ngaco ngirim game lain)
                    if (strpos(strtolower($item['game']), 'mobile legends') !== false) {
                        
                        $kode = $item['code'];
                        $nama = $item['name'];
                        
                        // Ambil harga 'basic' (karena lu kemungkinan daftar tipe akun biasa)
                        $harga_modal = $item['price']['basic'];
                        $status = $item['status'];
                        
                        // Simulasi keuntungan BingO
                        $harga_jual = $harga_modal + 2500;
                        
                        echo "<tr>";
                        echo "<td><strong>{$kode}</strong></td>";
                        echo "<td>{$nama}</td>";
                        echo "<td>Rp " . number_format($harga_modal, 0, ',', '.') . "</td>";
                        echo "<td>" . strtoupper($status) . "</td>";
                        echo "<td class='cuan'>Rp " . number_format($harga_jual, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='5'>Gagal menarik data: " . $result['message'] . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>