<?php
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // statement untuk menghindari SQL Injection
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();
            $stmt->close();

            echo "Registrasi berhasil. <a href='login.php'>Login di sini</a>";
        } else {
            echo "Terjadi kesalahan saat menyimpan data: " . $conn->error;
        }
    } else {
        echo "Username dan Password tidak boleh kosong.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="register-container">
    <h2>Daftar User Baru</h2>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>
    <!-- Form HTML -->
        <form method="POST">
    <input type="text" name="username" placeholder="Masukkan Username" required><br>
    <input type="password" name="password" placeholder="Masukkan Password" required><br>
    <input type="submit" value="Daftar">
        </form> 

    <br>
    <a href="login.php">← Kembali ke Login</a>
</div>
</body>
</html>

