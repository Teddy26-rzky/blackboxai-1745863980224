<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT id, nama, password, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: user/rooms.php');
            }
            exit();
        } else {
            $message = 'Email atau password salah.';
        }
    } else {
        $message = 'Semua field harus diisi.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login - Bale's Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
        <?php if ($message): ?>
            <div class="mb-4 text-red-600"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" class="space-y-4">
            <div>
                <label for="email" class="block mb-1 font-semibold">Email</label>
                <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2" />
            </div>
            <div>
                <label for="password" class="block mb-1 font-semibold">Password</label>
                <input type="password" id="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2" />
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Masuk</button>
        </form>
        <p class="mt-4 text-center">Belum punya akun? <a href="register.php" class="text-blue-600 hover:underline">Daftar di sini</a></p>
    </div>
</body>
</html>
