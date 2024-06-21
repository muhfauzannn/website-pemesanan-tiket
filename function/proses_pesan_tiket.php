<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_pemesan = $conn->real_escape_string($_POST['email_pemesan']);
    $usia = $conn->real_escape_string($_POST['usia']);
    $kd_jadwal = $conn->real_escape_string($_POST['kd_jadwal']);
    $kd_gerbong = $conn->real_escape_string($_POST['kd_gerbong']);
    $kd_bangku = $conn->real_escape_string($_POST['kd_bangku']);

    // Buat kode tiket unik dengan panjang sesuai
    $kd_tiket = "TK" . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Query untuk memasukkan data pemesanan
    $sql = "INSERT INTO tiket (kd, email_pemesan, usia, kd_jadwal, kd_gerbong, kd_bangku, status) 
            VALUES ('$kd_tiket', '$email_pemesan', '$usia', '$kd_jadwal', '$kd_gerbong', '$kd_bangku', 'Dipesan')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to success page with the ticket code
        header("Location: sukses_pesan_tiket.php?kd_tiket=" . urlencode($kd_tiket));
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

// Tutup koneksi
$conn->close();
?>
