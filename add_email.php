<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$query_user = "SELECT id FROM user_web WHERE username = '$username'";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);

if (!$user) {
    echo "User tidak ditemukan!";
    exit();
}

$user_id = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Cek apakah email sudah ada
    $check_query = "SELECT * FROM emails WHERE email = '$email' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('Email sudah ada!');
                window.location.href = 'add_email.php';
              </script>";
        exit();
    }

    // Simpan email ke database
    $query = "INSERT INTO emails (user_id, email) VALUES ('$user_id', '$email')";
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Email berhasil ditambahkan!');
                window.location.href = 'dashboard.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal menambahkan email!');
                window.location.href = 'add_email.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-6">Tambah Email</h2>
        <form method="POST" action="add_email.php">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Tambah</button>
        </form>
        <a href="dashboard.php" class="block text-center text-blue-500 mt-4 hover:underline">Kembali ke Dashboard</a>
    </div>
</body>
</html>
