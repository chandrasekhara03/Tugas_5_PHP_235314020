<?php
session_start();
require 'db.php'; 

// Tangkap data dari form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek ke database pakai prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['status'] = "login";
    header("location:todolist.php"); // ganti jika file tujuan login beda
} else {
    // Login gagal
    header("location:login.php?pesan=gagal");
}
?>
