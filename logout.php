<?php
session_start();  // Mulai sesi
session_unset();  // Hapus semua variabel sesi
session_destroy();  // Hancurkan sesi

// Arahkan kembali ke index.php (halaman login)
header("Location: index.php");
exit();  // Pastikan tidak ada kode lain yang dijalankan setelah pengalihan
?>
