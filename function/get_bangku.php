<?php
include '../includes/db.php';

$kelas = $_POST['kelas'];
$kereta = $_POST['kereta'];

$sql = "SELECT * FROM bangku WHERE kd_kelas='$kelas'";
$result = $conn->query($sql);

$bangku = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bangku[] = $row;
    }
}
echo json_encode($bangku);
?>
