<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$email_id = $_GET['email_id'];
$query = "SELECT * FROM garapan WHERE email_id = '$email_id' ORDER BY last_updated DESC";
$result = mysqli_query($conn, $query);

$query_email = "SELECT email FROM emails WHERE id = '$email_id'";
$email_result = mysqli_query($conn, $query_email);
$email_row = mysqli_fetch_assoc($email_result);
$email = $email_row['email'];

$edit_mode = false;
$garapan_id = "";
$garapan_name = "";
$garapan_point = "";

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM garapan WHERE id = '$edit_id'";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_row = mysqli_fetch_assoc($edit_result)) {
        $garapan_id = $edit_row['id'];
        $garapan_name = $edit_row['name'];
        $garapan_point = $edit_row['point'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['method'];
    $name = $_POST['name'];
    $point = $_POST['point'];
    $api = $_POST['api'] ?? "";
    $token = $_POST['token'] ?? "";

    if ($method === "api" && !empty($api)) {
        if ($api === "dawn") {
            $api_url = "https://dawn-validator.com/api/get-point?email=$email&token=$token";
        } else {
            $api_url = "https://example.com/get-point?email=$email";
        }
        
        $api_response = file_get_contents($api_url);
        $api_data = json_decode($api_response, true);
        
        if ($api_data && isset($api_data['point'])) {
            $point = $api_data['point'];
        }
    }

    if (!empty($_POST['garapan_id'])) {
        $garapan_id = $_POST['garapan_id'];
        $query = "UPDATE garapan SET name='$name', point='$point' WHERE id='$garapan_id'";
        mysqli_query($conn, $query);
    } else {
        $query = "INSERT INTO garapan (email_id, name, point) VALUES ('$email_id', '$name', '$point')";
        mysqli_query($conn, $query);
    }
    
    header("Location: garapan.php?email_id=$email_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Akun <?php echo $email; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h2 class="text-2xl font-bold mb-4">Data akun <?php echo $email; ?></h2>
<!-- Tambahkan tombol kembali ke dashboard di bawah tabel atau form dengan posisi kanan, warna merah, dan jarak -->
<div class="flex justify-end mb-4">
    <a href="dashboard.php" class="inline-block bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
        Kembali ke Dashboard
    </a>
</div>


        <div class="bg-white p-4 rounded shadow-md mb-6">
            <h3 class="text-lg font-semibold mb-3">Tambah/Edit Garapan</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="garapan_id" value="<?php echo $garapan_id; ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pilih Metode:</label>
                    <select name="method" id="method" class="mt-1 p-2 border rounded w-full" onchange="toggleMethod()">
                        <option value="manual">Input Manual</option>
                        <option value="api">Ambil dari API</option>
                    </select>
                </div>
                
                <div id="api_section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Pilih API:</label>
                    <select name="api" id="api" class="mt-1 p-2 border rounded w-full" onchange="toggleApiInput()">
                        <option value="">-- Pilih API --</option>
                        <option value="dawn">Dawn Validator</option>
                    </select>
                    <div id="token_input" class="hidden mt-2">
                        <label class="block text-sm font-medium text-gray-700">Token:</label>
                        <input type="text" name="token" class="mt-1 p-2 border rounded w-full">
                    </div>
                </div>
                
                <div id="manual_input">
                    <label class="block text-sm font-medium text-gray-700">Nama Garapan</label>
                    <input type="text" name="name" value="<?php echo $garapan_name; ?>" class="mt-1 p-2 border rounded w-full" required>
                    
                    <label class="block text-sm font-medium text-gray-700">Poin</label>
                    <input type="number" name="point" value="<?php echo $garapan_point; ?>" class="mt-1 p-2 border rounded w-full" required>
                </div>
                
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Simpan</button>
            </form>
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nama Garapan</th>
                    <th class="px-4 py-2 text-left">Total Poin</th>
                    <th class="px-4 py-2 text-left">Terakhir Update</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo $row['name']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['point']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['last_updated']; ?></td>
                        <td class="px-4 py-2">
                            <a href="garapan.php?email_id=<?php echo $email_id; ?>&edit_id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white py-1 px-3 rounded">Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleMethod() {
            let method = document.getElementById("method").value;
            document.getElementById("manual_input").style.display = method === "manual" ? "block" : "none";
            document.getElementById("api_section").style.display = method === "api" ? "block" : "none";
        }
        function toggleApiInput() {
            let api = document.getElementById("api").value;
            document.getElementById("token_input").style.display = api === "dawn" ? "block" : "none";
        }
    </script>
</body>
</html>