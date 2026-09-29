<?php
// WAJIB ADA DI PALING ATAS BIAR ETALASE KANAN MUNCUL
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

$katalog_game = [];
$sql = "SELECT k.nama_game, p.id_produk, p.nama_produk, p.harga_jual 
        FROM produk p
        JOIN kategori k ON p.id_kategori = k.id_kategori
        WHERE k.kode_game = 'mlbb'"; 

$stmt = $pdo->query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $game = $row['nama_game'];
    $katalog_game[$game][] = ["id" => $row['id_produk'], "nama" => $row['nama_produk'], "harga" => $row['harga_jual']];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Top Up Mobile Legends - BINGO</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: auto; }

        .header-bingo { background-color: #ffe600; border: 4px solid #000; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; font-weight: 900; font-size: 24px; margin-bottom: 20px; box-shadow: 5px 5px 0px #000; }
        .btn-kembali { font-size: 14px; text-decoration: none; color: #000; font-weight: bold; border-bottom: 2px solid #000; }

        .layout-wrapper { display: flex; gap: 30px; align-items: flex-start; }
        .kolom-kiri { flex: 1; display: flex; flex-direction: column; gap: 20px; }
        .kolom-kanan { flex: 1.5; }
        
        .box-brutalist { border: 4px solid #000; background: #fff; padding: 20px; box-shadow: 5px 5px 0px #000; }
        .box-title { display: flex; align-items: center; gap: 10px; border-bottom: 3px solid #000; padding-bottom: 10px; margin-top: 0; font-weight: 900; text-transform: uppercase; font-size: 16px; }
        .badge-angka { background: #000; color: #fff; padding: 2px 8px; font-size: 16px; }

        .input-group { display: flex; flex-direction: column; gap: 15px; margin-top: 15px; }
        .input-row { display: flex; gap: 10px; }
        .input-field { border: 2px solid #000; padding: 12px; font-size: 14px; width: 100%; box-sizing: border-box; font-weight: bold; }
        .input-field:focus { outline: none; border-color: #e74c3c; background: #fafafa; }
        
        .btn-cek { background: #000; color: #fff; border: 2px solid #000; padding: 10px 20px; font-weight: bold; cursor: pointer; transition: 0.2s; white-space: nowrap; }
        .btn-cek:hover { background: #e74c3c; border-color: #e74c3c; }

        #hasil-nickname { display: none; margin-top: 10px; padding: 10px; border: 2px solid #000; font-weight: bold; font-size: 14px; }
        .sukses { background-color: #a8e6cf; color: #000; }
        .gagal { background-color: #ff8b94; color: #000; }

        .grid-diamond { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; margin-top: 15px; }
        .card-label { cursor: pointer; display: block; }
        .card-label input[type="radio"] { display: none; }
        .card { border: 3px solid #000; padding: 15px; text-align: center; background: #fff; transition: 0.1s; height: 100%; box-sizing: border-box; display: flex; flex-direction: column; justify-content: center; align-items: center;}
        
        .card-label input[type="radio"]:checked + .card { background-color: #ffe600; box-shadow: 4px 4px 0px #000; transform: translate(-2px, -2px); }
        .card-nama { font-size: 14px; font-weight: bold; margin: 0 0 8px 0; color: #000;}
        .card-harga { font-size: 15px; color: #e74c3c; font-weight: 900; margin: 0; }

        .btn-beli { background: #e74c3c; color: white; border: 4px solid #000; padding: 15px; width: 100%; font-size: 20px; font-weight: 900; cursor: pointer; text-transform: uppercase; box-shadow: 5px 5px 0px #000; margin-top: 20px; transition: 0.1s; }
        .btn-beli:hover { background: #c0392b; }
        .btn-beli:active { transform: translate(4px, 4px); box-shadow: 1px 1px 0px #000; }
    </style>
</head>
<body>

<div class="container">
    
    <div class="header-bingo">
        <div>BINGO</div>
        <a href="#" class="btn-kembali">Kembali ke Beranda</a>
    </div>

    <form action="proses_transaksi.php" method="POST" class="layout-wrapper">

        <!-- KOLOM KIRI -->
        <div class="kolom-kiri">
            <div class="box-brutalist">
                <h2 class="box-title"><span class="badge-angka">1</span> MASUKKAN DATA AKUN</h2>
                <div class="input-group">
                    <div class="input-row">
                        <input type="number" id="input_user_id" name="user_id" class="input-field" placeholder="User ID" required>
                        <input type="number" id="input_zone_id" name="zone_id" class="input-field" placeholder="Zone ID" required>
                    </div>
                    <button type="button" class="btn-cek" onclick="cekNickname()">CEK NICKNAME</button>
                    <div id="hasil-nickname"></div>
                </div>
            </div>

            <div class="box-brutalist">
                <h2 class="box-title"><span class="badge-angka">3</span> DETAIL KONTAK & PROMO</h2>
                <div class="input-group">
                    <input type="email" name="email" class="input-field" placeholder="Alamat Email (Wajib untuk struk)" required>
                    <input type="number" name="whatsapp" class="input-field" placeholder="No. WhatsApp (Opsional)">
                    <input type="text" name="promo" class="input-field" placeholder="Kode Promo (Opsional)">
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="kolom-kanan">
            <?php foreach ($katalog_game as $game => $items) : ?>
                <div class="box-brutalist" style="margin-bottom: 20px;">
                    <h2 class="box-title"><span class="badge-angka">2</span> PILIH NOMINAL</h2>
                    <div class="grid-diamond">
                        <?php foreach ($items as $item) : ?>
                            <label class="card-label">
                                <input type="radio" name="id_produk" value="<?= $item['id'] ?>" required>
                                <div class="card">
                                    <h3 class="card-nama"><?= htmlspecialchars($item['nama']) ?></h3>
                                    <p class="card-harga">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn-beli">BELI SEKARANG</button>
        </div>

    </form>
</div>

<!-- MESIN CEK ID -->
<script>
    function cekNickname() {
        let userId = document.getElementById('input_user_id').value;
        let zoneId = document.getElementById('input_zone_id').value;
        let kotakHasil = document.getElementById('hasil-nickname');

        if (!userId || !zoneId) {
            alert("Harap isi User ID dan Zone ID terlebih dahulu!");
            return;
        }

        kotakHasil.style.display = 'block';
        kotakHasil.className = ''; 
        kotakHasil.innerHTML = 'Memeriksa ID...';

        let formData = new FormData();
        formData.append('user_id', userId);
        formData.append('zone_id', zoneId);

        // Menembak ke file cek_id.php di folder ngetopup
        fetch('http://localhost/ngetopup/cek_id.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if(data.status === true) {
                kotakHasil.className = 'sukses';
                kotakHasil.innerHTML = '✅ Nickname: ' + data.nickname;
            } else {
                kotakHasil.className = 'gagal';
                kotakHasil.innerHTML = '❌ ' + data.pesan;
            }
        })
        .catch(error => {
            kotakHasil.className = 'gagal';
            kotakHasil.innerHTML = '❌ Terjadi kesalahan sistem.';
        });
    }
</script>

</body>
</html>