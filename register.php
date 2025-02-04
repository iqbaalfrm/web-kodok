<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah username sudah ada
    $query = "SELECT * FROM user_web WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                Swal.fire('Gagal!', 'Username sudah digunakan!', 'error');
              </script>";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO user_web (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Registrasi berhasil!',
                        icon: 'success'
                    }).then(() => {
                        window.location = 'index.php';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat registrasi!', 'error');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-80">
        <h2 class="text-xl font-bold mb-6">Register</h2>
        <form method="POST" action="register.php">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Register</button>
        </form>
        <p class="mt-4 text-sm text-center">Sudah punya akun? <a href="index.php" class="text-blue-500">Login</a></p>
    </div>
</body>
</html>
