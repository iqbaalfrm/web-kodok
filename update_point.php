<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $garapan_id = $_POST['garapan_id'];
    $new_point = $_POST['point'];
    $query = "UPDATE garapan SET point = '$new_point' WHERE id = '$garapan_id'";
    mysqli_query($conn, $query);
    header("Location: garapan.php?email_id=" . $_POST['email_id']);
}

$garapan_id = $_GET['garapan_id'];
$query = "SELECT * FROM garapan WHERE id = '$garapan_id'";
$result = mysqli_query($conn, $query);
$garapan = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Point</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h2 class="text-2xl font-bold mb-6">Update Point for Garapan</h2>
        <form method="POST" action="update_point.php" class="space-y-4">
            <input type="hidden" name="garapan_id" value="<?php echo $garapan['id']; ?>">
            <input type="hidden" name="email_id" value="<?php echo $_GET['email_id']; ?>">
            <div>
                <label for="point" class="block text-sm font-medium text-gray-700">New Point</label>
                <input type="number" name="point" id="point" class="mt-1 p-2 border border-gray-300 rounded w-full" value="<?php echo $garapan['point']; ?>" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Update Point</button>
        </form>
    </div>
</body>
</html>
