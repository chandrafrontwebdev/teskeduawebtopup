<?php
// Tentukan margin keuntungan flat
$margin = 1500;

// Kelompokkan data berdasarkan kategori di UI
$katalog_ml = [
    "Diamond Biasa (Instant)" => [
        ["kode" => "MLA5-S13", "nama" => "5 Diamonds", "modal" => 1484],
        ["kode" => "MLBR55-S13", "nama" => "55 Diamonds (First Top Up)", "modal" => 12097],
        ["kode" => "MLBR86-S13", "nama" => "86 Diamonds", "modal" => 18776],
        ["kode" => "MLBR165-S13", "nama" => "165 Diamonds (First Top Up)", "modal" => 35599],
        ["kode" => "MLBR257-S13", "nama" => "257 Diamonds", "modal" => 55516],
        ["kode" => "MLBR344-S13", "nama" => "344 Diamonds", "modal" => 77224],
        ["kode" => "MLBR516-S13", "nama" => "516 Diamonds", "modal" => 111503],
        ["kode" => "MLBR706-S13", "nama" => "706 Diamonds", "modal" => 148670],
        ["kode" => "MLBR1050-S13", "nama" => "1050 Diamonds", "modal" => 235527],
        ["kode" => "MLBR2195-S13", "nama" => "2195 Diamonds", "modal" => 445853],
        ["kode" => "MLBR5532-S13", "nama" => "5532 Diamonds", "modal" => 1114555],
        ["kode" => "MLBR9288-S13", "nama" => "9288 Diamonds", "modal" => 1857749]
    ],
    "Pass & Membership" => [
        ["kode" => "MLBRWDP-S13", "nama" => "Weekly Diamond Pass", "modal" => 23837],
        ["kode" => "MLBRTW-S13", "nama" => "Twilight Pass", "modal" => 140012]
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Mobile Legends</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .kategori-section { margin-bottom: 30px; }
        .kategori-title { border-bottom: 2px solid #333; padding-bottom: 5px; color: #333; }
        .grid-container { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px; }
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 220px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card-kode { font-size: 0.8em; color: #888; display: block; margin-bottom: 5px; }
        .card-nama { font-size: 1em; font-weight: bold; margin: 0 0 10px 0; min-height: 40px; }
        .card-harga { font-size: 1.2em; color: #e74c3c; font-weight: bold; margin: 0; }
    </style>
</head>
<body>

    <h1>Top Up Mobile Legends</h1>

    <!-- Loop Pertama: Mengambil Kategori (cth: Diamond Biasa, Pass & Membership) -->
    <?php foreach ($katalog_ml as $kategori => $items) : ?>
        
        <div class="kategori-section">
            <h2 class="kategori-title"><?= $kategori ?></h2>
            <div class="grid-container">
                
                <!-- Loop Kedua: Mengambil item di dalam kategori tersebut -->
                <?php foreach ($items as $item) : 
                    // Hitung harga jual otomatis di frontend
                    $harga_jual = $item['modal'] + $margin;
                ?>
                    <div class="card">
                        <span class="card-kode"><?= $item['kode'] ?></span>
                        <h3 class="card-nama"><?= $item['nama'] ?></h3>
                        <p class="card-harga">Rp <?= number_format($harga_jual, 0, ',', '.') ?></p>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    <?php endforeach; ?>

</body>
</html>