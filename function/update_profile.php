<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../includes/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $tgl_lahir = $conn->real_escape_string($_POST['tgl_lahir']);
    $no_identitas = $conn->real_escape_string($_POST['no_identitas']);
    $profile_picture = $_FILES['profile_picture'];

    try {
        $conn->begin_transaction();

        // Handle profile picture upload
        if ($profile_picture['error'] == 0) {
            $kd_gambar = "GP" . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Ensure the length is 5 characters
            $file = file_get_contents($profile_picture['tmp_name']);

            // Check if user already has a custom profile picture
            $sql = "SELECT kd_gambar FROM pengguna WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user['kd_gambar'] !== 'GP001') {
                // Delete the old profile picture
                $sql = "DELETE FROM gambar_pengguna WHERE kd = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $user['kd_gambar']);
                $stmt->execute();
            }

            // Insert the new profile picture
            $sql = "INSERT INTO gambar_pengguna (kd, file) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sb', $kd_gambar, $file);
            $stmt->send_long_data(1, $file);
            $stmt->execute();

            // Update user information with the new profile picture
            $sql = "UPDATE pengguna SET nama = ?, sex = ?, tgl_lahir = ?, no_identitas = ?, kd_gambar = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $nama, $sex, $tgl_lahir, $no_identitas, $kd_gambar, $email);
        } else {
            // Update user information without changing the profile picture
            $sql = "UPDATE pengguna SET nama = ?, sex = ?, tgl_lahir = ?, no_identitas = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $nama, $sex, $tgl_lahir, $no_identitas, $email);
        }

        if ($stmt->execute()) {
            $conn->commit();
            header("Location: ../profile.php");
        } else {
            throw new Exception("Error updating record: " . $stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage());
        echo "Failed to update profile: " . $e->getMessage();
    }
}
?>
