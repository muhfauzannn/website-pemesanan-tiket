<?php
session_start();
include '../includes/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cegah SQL Injection
    $email = $conn->real_escape_string($email);

    // Query ke database untuk memeriksa user
    $sql = "SELECT * FROM pengguna WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['email'] = $email; // Simpan email dalam sesi
            header("Location: ../dashboard.php");
            exit(); 
        } else {
            // Password salah
            $_SESSION['error'] = "Email atau password salah!";
            header("Location: ../index.php");
            exit(); 
        }
    } else {
        // User tidak ditemukan
        $_SESSION['error'] = "Email atau password salah!";
        header("Location: ../index.php");
        exit(); 
    }
}

// Tutup koneksi
$conn->close();
?>
