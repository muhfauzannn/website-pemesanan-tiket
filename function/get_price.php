<?php
include '../includes/db.php';

if (isset($_POST['kd_jadwal']) && isset($_POST['kd_kelas'])) {
    $kd_jadwal = $_POST['kd_jadwal'];
    $kd_kelas = $_POST['kd_kelas'];

    $sql_price = "SELECT Hitung_Harga('$kd_jadwal', '$kd_kelas') AS harga";
    $result_price = $conn->query($sql_price);
    if ($result_price->num_rows > 0) {
        $row_price = $result_price->fetch_assoc();
        echo json_encode(array('harga' => $row_price['harga']));
    } else {
        echo json_encode(array('harga' => 0));
    }
} else {
    echo json_encode(array('harga' => 0));
}
?>
