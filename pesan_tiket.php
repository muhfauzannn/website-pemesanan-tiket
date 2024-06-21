<?php

session_start();
include './includes/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email_pemesan = $_SESSION['email'];

if (isset($_GET['kd_jadwal'])) {
    $kd_jadwal = $_GET['kd_jadwal'];
    $sql = "SELECT * FROM jadwal_display WHERE kd_jadwal = '$kd_jadwal'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Jadwal tidak ditemukan.";
        exit();
    }
} else {
    echo "Kode jadwal tidak ditemukan.";
    exit;
}

// Get kd_kereta from jadwal_keberangkatan table
$kd_kereta = '';
$sql_kereta = "SELECT kd_kereta FROM jadwal_keberangkatan WHERE kd = '$kd_jadwal'";
$result_kereta = $conn->query($sql_kereta);
if ($result_kereta->num_rows > 0) {
    $row_kereta = $result_kereta->fetch_assoc();
    $kd_kereta = $row_kereta['kd_kereta'];
}

// Ambil data kelas dari database
$sql_kelas = "SELECT * FROM kelas";
$result_kelas = $conn->query($sql_kelas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="search-container">
        <div class="search">
            <div class="pesan-title">
                <a href="./dashboard.php">
                    <svg id="fi_9312240" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m411.5 281h-298c-13.81 0-25-11.19-25-25s11.19-25 25-25h298c13.81 0 25 11.19 25 25s-11.19 25-25 25z"></path></g><g><path d="m227.99 399.25c-6.08 0-12.18-2.21-16.99-6.67l-127.5-118.25c-5.14-4.77-8.05-11.48-8-18.5.05-7.01 3.04-13.69 8.25-18.39l131-118.25c10.25-9.25 26.06-8.44 35.31 1.81s8.44 26.06-1.81 35.31l-110.72 99.94 107.47 99.67c10.12 9.39 10.72 25.21 1.33 35.33-4.93 5.31-11.62 8-18.34 8z"></path></g></g></svg>
                </a>
                <h1>Pesan Tiket</h1>
                <div class="spacer"></div>
            </div>
        </div>
        <div class="text-dashboard">
            <p>Detail Pemesanan</p>
        </div>
    </div>
    
    <div class="blue-background-img">
        <img id="background-img" src="./assets/image.jpeg">
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
            <input type="hidden" name="kd_jadwal" value="<?php echo $kd_jadwal; ?>">
            
            <label class="first-form">Email Pemesan</label>
            <input class="box-form" type="email" name="email_pemesan" value="<?php echo htmlspecialchars($email_pemesan); ?>" readonly>

            <label>Usia</label>
            <input class="box-form" type="number" name="usia" required>

            <label>Kelas</label>
            <select class="box-form" name="kd_kelas" id="kd_kelas" required>
                <option value="">Pilih Kelas</option>
                <?php
                if ($result_kelas->num_rows > 0) {
                    while ($row_kelas = $result_kelas->fetch_assoc()) {
                        echo '<option value="' . $row_kelas['kd'] . '">' . $row_kelas['nama'] . '</option>';
                    }
                }
                ?>
            </select>

            <label>Gerbong</label>
            <select class="box-form" name="kd_gerbong" id="kd_gerbong" required>
                <option value="">Pilih Gerbong</option>
            </select>

            <label>Bangku</label>
            <select class="box-form" name="kd_bangku" id="kd_bangku" required>
                <option value="">Pilih Bangku</option>
            </select>
            
            <div id="ticket-price-container">
                <span>Total Harga</span></p>
                <span id="ticket-price">
                    <?php echo htmlspecialchars(number_format($ticket_price, 0, ',', '.')); ?>
                </span>
            </div>      
            
            <input id="konfirmasi-pesan" type="submit" value="Pesan Tiket">
        </form>
    </div>

    <script>
        $(document).ready(function(){
            var kdKereta = "<?php echo $kd_kereta; ?>"; // Get kd_kereta from PHP
            console.log("kdKereta: " + kdKereta);

            $('#kd_kelas').change(function(){
                var kelasId = $(this).val();
                console.log("kelasId: " + kelasId);
                var gerbongSelect = $('#kd_gerbong');
                var bangkuSelect = $('#kd_bangku');

                // Reset gerbong dan bangku
                gerbongSelect.html('<option value="">Pilih Gerbong</option>');
                bangkuSelect.html('<option value="">Pilih Bangku</option>');

                if (kelasId) {
                    // Ambil data gerbong berdasarkan kelas dan kereta yang dipilih
                    $.ajax({
                        url: 'function/get_gerbong.php',
                        type: 'POST',
                        data: {
                            kelas: kelasId,
                            kereta: kdKereta
                        },
                        dataType: 'json',
                        success: function(data){
                            console.log("gerbong data: ", data);
                            $.each(data, function(key, value){
                                console.log("Appending option - key: ", key, " value: ", value);
                                gerbongSelect.append('<option value="'+ value.kd +'">'+ value.kd +'</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Error: " + textStatus + " " + errorThrown);
                        }
                    });

                    // Ambil data bangku berdasarkan kelas yang dipilih
                    $.ajax({
                        url: 'function/get_bangku.php',
                        type: 'POST',
                        data: {kelas: kelasId},
                        dataType: 'json',
                        success: function(data){
                            console.log("bangku data: ", data);
                            $.each(data, function(key, value){
                                bangkuSelect.append('<option value="'+ value.kd +'">'+ value.no +'</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Error: " + textStatus + " " + errorThrown);
                        }
                    });

                    // Ambil harga tiket berdasarkan jadwal dan kelas yang dipilih
                    $.ajax({
                        url: 'function/get_price.php',
                        type: 'POST',
                        data: {
                            kd_jadwal: "<?php echo $kd_jadwal; ?>",
                            kd_kelas: kelasId
                        },
                        dataType: 'json',
                        success: function(data){
                            console.log("price data: ", data);
                            $('#ticket-price').text(new Intl.NumberFormat('id-ID').format(data.harga));
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Error: " + textStatus + " " + errorThrown);
                        }
                    });
                }
            });

            $('#kd_gerbong').change(function(){
                var gerbongId = $(this).val();
                console.log("gerbongId: " + gerbongId);
                var bangkuSelect = $('#kd_bangku');

                if (gerbongId) {
                    // Ambil data bangku berdasarkan gerbong yang dipilih
                    $.ajax({
                        url: 'function/get_bangku.php',
                        type: 'POST',
                        data: {gerbong: gerbongId},
                        dataType: 'json',
                        success: function(data){
                            console.log("bangku data: ", data);
                            $.each(data, function(key, value){
                                bangkuSelect.append('<option value="'+ value.kd +'">'+ value.no +'</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Error: " + textStatus + " " + errorThrown);
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>

