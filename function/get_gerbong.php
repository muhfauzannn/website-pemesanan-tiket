<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/db.php';

$kelas = $_POST['kelas'];
$kereta = $_POST['kereta'];

if (isset($kelas) && isset($kereta)) {
    $sql = "SELECT kd FROM gerbong WHERE kd_kelas='$kelas' AND kd_kereta='$kereta'";
    $result = $conn->query($sql);

    $gerbong = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $gerbong[] = $row;
        }
    }
    echo json_encode($gerbong);
} else {
    echo json_encode([]);
}
?>
