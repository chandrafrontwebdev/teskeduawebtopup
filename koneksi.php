<?php
// Pengaturan standar XAMPP
$host = "localhost";
$user = "root";       // Username bawaan XAMPP
$pass = "";           // Password bawaan kosong
$db   = "db_ngetopup"; // Nama database yang lu bikin tadi

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil (Percabangan IF - Materi Pertemuan 7)
if (!$koneksi) {
    die("Aduh bro, database gagal nyambung: " . mysqli_connect_error());
}
?>