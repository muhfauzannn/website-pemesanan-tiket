<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama-user'];
    $email = $_POST['email-user'];
    $identitas = $_POST['identitas-user'];
    $password = $_POST['password-user'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cegah SQL Injection
    $nama = $conn->real_escape_string($nama);
    $email = $conn->real_escape_string($email);
    $identitas = $conn->real_escape_string($identitas);
    $hashed_password = $conn->real_escape_string($hashed_password);

    // Debugging
    echo "Nama: $nama<br>";
    echo "Email: $email<br>";
    echo "Identitas: $identitas<br>";
    echo "Password Hash: $hashed_password<br>";

    // Query untuk memasukkan data pengguna baru
    $sql = "INSERT INTO pengguna (email, password, nama, no_identitas) VALUES ('$email', '$hashed_password', '$nama', '$identitas')";

    if ($conn->query($sql) === TRUE) {
        // Pendaftaran berhasil
        echo "Pendaftaran berhasil!";
        // Redirect ke halaman login
        header("Location: ../index.php");
    } else {
        // Gagal daftar
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tutup koneksi
$conn->close();
?>
