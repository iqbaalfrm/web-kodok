<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_id = $_POST['email_id'];
    $name = $_POST['name'];
    $query = "INSERT INTO garapan (email_id, name) VALUES ('$email_id', '$name')";
    mysqli_query($conn, $query);
    header("Location: garapan.php?email_id=$email_id");
}

$email_id = $_GET['email_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Garapan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-8">
        <h2 class="text-2xl font-bold mb-6">Add Garapan</h2>
        <form method="POST" action="add_garapan.php" class="space-y-4">
            <input type="hidden" name="email_id" value="<?php echo $email_id; ?>">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Garapan Name</label>
                <input type="text" name="name" id="name" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Add Garapan</button>
        </form>
    </div>
</body>
</html>
