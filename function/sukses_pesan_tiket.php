<?php
session_start();
include '../includes/db.php';

// Get the ticket code from the URL parameter
if (isset($_GET['kd_tiket'])) {
    $kd_tiket = $conn->real_escape_string($_GET['kd_tiket']);
} else {
    echo "Kode tiket tidak ditemukan.";
    exit();
}

// Fetch the ticket information based on the ticket code
$sql = "SELECT * FROM tiket_display WHERE kd = '$kd_tiket'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Tiket tidak ditemukan.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Berhasil Dipesan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/style.css">
    <style>
        body,html{
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <div class="search">
            <div class="pesan-title">
                <h1>Sukses!</h1>
                <div class="spacer"></div>
            </div>
        </div>
        <div class="text-dashboard">
            <p>Tiketmu berhasil tercetak!</p>
        </div>
    </div>

    <div class="blue-background-img">
        <img id="background-img" src="../assets/image.jpeg">
    </div>

    <div class="container">
        <div class="content">
            <div class="info-pesan">
                <p id="asal-tujuan-pesan"><?php echo htmlspecialchars($row['asal'] . " - " . $row['tujuan']); ?></p>
                <p><?php echo htmlspecialchars(date("l, d F Y", strtotime($row['tanggal']))); ?></p>
                <p><?php echo htmlspecialchars($row['berangkat'] . " - " . $row['tiba']); ?></p>
            </div>
        </div>
    </div>

    <div class="container-pesan">
        <form action="./function/proses_pesan_tiket.php" id="pesan-form" method="post">
            
            <label class="first-form">Kode Tiket</label>
            <input class="box-form" type="email" name="kd_tiket" value="<?php echo htmlspecialchars($row['kd']); ?>" readonly>
            
            <label>Tanggal Keberangkatan</label>
            <input class="box-form" type="date" name="tgl" value="<?php echo htmlspecialchars($row['tanggal']); ?>" readonly>
            
            <div class="container-center">
                <div class="container-grid">
                    <div class="boxform-item">
                        <p>Asal</p>
                        <input type="text" name="asal" value="<?php echo htmlspecialchars($row['asal']); ?>" readonly>
                    </div>
                    <div class="boxform-item">
                        <p>Tujuan</p>
                        <input type="text" name="tujuan" value="<?php echo htmlspecialchars($row['tujuan']); ?>" readonly>
                    
                    </div>
                    <div class="boxform-item">
                        <p>Berangkat</p>
                        <input type="text" name="berangkat" value="<?php echo htmlspecialchars($row['berangkat']); ?>" readonly>
                    
                    </div>
                    <div class="boxform-item">
                        <p>Tiba</p>
                        <input type="text" name="tiba" value="<?php echo htmlspecialchars($row['tiba']); ?>" readonly>
                    
                    </div>
                </div>
            </div>

            <div id="ticket-price-container">
                <span>Total Harga</span></p>
                <span id="ticket-price">
                    <?php echo htmlspecialchars($row['harga']); ?>
                </span>
            </div>      
            <a href="../dashboard.php">
                <div id="konfirmasi-pesan">Kembali ke Dashboard</div>
            </a>
            <!-- <input id="konfirmasi-pesan" type="submit" value="Pesan Tiket"> -->
        </form>
    </div>


</body>
</html>