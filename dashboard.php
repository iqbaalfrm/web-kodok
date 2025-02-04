<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user']; // Gunakan username dari session
$query_user = "SELECT id FROM user_web WHERE username = '$username'";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);

if (!$user) {
    echo "User tidak ditemukan!";
    exit();
}

$user_id = $user['id'];

// Ambil daftar email berdasarkan user_id
$query_email = "SELECT * FROM emails WHERE user_id = '$user_id'";
$result_email = mysqli_query($conn, $query_email);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-800">Rekap Airdrop Kodok</h1>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-all">
            Logout
        </a>
    </nav>

    <!-- Container -->
    <div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

        <!-- Tombol Tambah Email -->
        <a href="add_email.php" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition-all">
            + Tambah Email
        </a>

        <!-- Tabel Email -->
        <div class="overflow-x-auto mt-6">
            <table class="w-full table-auto border border-gray-300 shadow-md rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">Email</th>
                        <th class="px-4 py-2 text-center text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_email)) { ?>
                        <tr class="border-t hover:bg-gray-100 transition-all">
                            <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="px-4 py-3 text-center">
                                <a href="garapan.php?email_id=<?php echo $row['id']; ?>" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-all shadow-sm">
                                    View Garapan
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
