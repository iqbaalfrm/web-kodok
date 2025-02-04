<?php
$servername = "homelaundry.my.id";
$username = "homelaun_tumanina";  
$password = "Tumanina123";      
$dbname = "homelaun_tumanina";  // Nama database yang digunakan

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
