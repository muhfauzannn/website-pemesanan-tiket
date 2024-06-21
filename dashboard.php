<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './includes/db.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM jadwal_display WHERE tujuan LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM jadwal_display";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css">

    <style>
        html, body{
            overflow: visible;
        }
        .container{
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .content{
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
        }
        .kereta{
            width: 90%;
            height: 130px;
            background-color: #2D2A70;
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            margin-top: 2rem;
        }
        .image-kereta{
            overflow: hidden;
            width: 150px;
            height: 100%;
        }
        .image-kereta > img{
            width: 250px;
            transform: translateX(-50%);
        }
        .keterangan-kereta{
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .keterangan-kereta > *{
            color: white;
            margin-bottom: 10px;
        }
        .ket-jadwal{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .ket-jadwal > p{
            font-size: 10px;
        }
        #cek-tiket{
            width: 100px;
            height: 30px;
            color: white;
            background-color: #FF7325;
            border-radius: 30px;
            border: 0;
            cursor: pointer;
        }
        .content-dashboard{
            display: flex;
            width: 80%;
            flex-direction: column;
            align-items: center;
            margin-bottom: 8rem;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <div class="search">
            <form action="dashboard.php" method="GET">
                <input type="text" name="search" placeholder="Cari stasiun tujuan" value="<?php echo htmlspecialchars($search); ?>" id="search-form">
                <input type="submit" value="Cari" style="display: none;">
            </form>
        </div>
        <div class="text-dashboard">
            <p>Jadwal Keberangkatan</p>
        </div>
    </div>

    <div class="container">
        <div class="content-dashboard">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="kereta">';
                    echo '    <div class="image-kereta">';
                    echo '        <img src="./assets/image.jpeg">';
                    echo '    </div>';
                    echo '    <div class="keterangan-kereta">';
                    echo '        <p id="asal-tujuan">' . $row["asal"] . ' - ' . $row["tujuan"] . '</p>';
                    echo '        <div class="ket-jadwal">';
                    echo '            <p id="Tanggal-kereta">' . date("l, d F Y", strtotime($row["tanggal"])) . '</p>';
                    echo '            <p id="jam-keberangkatan">' . $row["berangkat"] . ' - ' . $row["tiba"] . '</p>';
                    echo '        </div>';
                    echo '        <form action="pesan_tiket.php" method="GET">';
                    echo '            <input type="hidden" name="kd_jadwal" value="' . $row["kd_jadwal"] . '">';
                    echo '            <input type="submit" value="Cek Tiket" id="cek-tiket">';
                    echo '        </form>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo "Tidak ada jadwal kereta.";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <div class="navbar">
        <a href="#">
            <div class="item-navbar">
                <svg style="fill: #2D2A70;" height="511pt" viewBox="0 1 511 511.999" width="511pt" xmlns="http://www.w3.org/2000/svg" id="fi_1946488"><path d="m498.699219 222.695312c-.015625-.011718-.027344-.027343-.039063-.039062l-208.855468-208.847656c-8.902344-8.90625-20.738282-13.808594-33.328126-13.808594-12.589843 0-24.425781 4.902344-33.332031 13.808594l-208.746093 208.742187c-.070313.070313-.144532.144531-.210938.214844-18.28125 18.386719-18.25 48.21875.089844 66.558594 8.378906 8.382812 19.441406 13.234375 31.273437 13.746093.484375.046876.96875.070313 1.457031.070313h8.320313v153.695313c0 30.417968 24.75 55.164062 55.167969 55.164062h81.710937c8.285157 0 15-6.71875 15-15v-120.5c0-13.878906 11.292969-25.167969 25.171875-25.167969h48.195313c13.878906 0 25.167969 11.289063 25.167969 25.167969v120.5c0 8.28125 6.714843 15 15 15h81.710937c30.421875 0 55.167969-24.746094 55.167969-55.164062v-153.695313h7.71875c12.585937 0 24.421875-4.902344 33.332031-13.8125 18.359375-18.367187 18.367187-48.253906.027344-66.632813zm-21.242188 45.421876c-3.238281 3.238281-7.542969 5.023437-12.117187 5.023437h-22.71875c-8.285156 0-15 6.714844-15 15v168.695313c0 13.875-11.289063 25.164062-25.167969 25.164062h-66.710937v-105.5c0-30.417969-24.746094-55.167969-55.167969-55.167969h-48.195313c-30.421875 0-55.171875 24.75-55.171875 55.167969v105.5h-66.710937c-13.875 0-25.167969-11.289062-25.167969-25.164062v-168.695313c0-8.285156-6.714844-15-15-15h-22.328125c-.234375-.015625-.464844-.027344-.703125-.03125-4.46875-.078125-8.660156-1.851563-11.800781-4.996094-6.679688-6.679687-6.679688-17.550781 0-24.234375.003906 0 .003906-.003906.007812-.007812l.011719-.011719 208.847656-208.839844c3.234375-3.238281 7.535157-5.019531 12.113281-5.019531 4.574219 0 8.875 1.78125 12.113282 5.019531l208.800781 208.796875c.03125.03125.066406.0625.097656.09375 6.644531 6.691406 6.632813 17.539063-.03125 24.207032zm0 0"></path></svg>
            </div>
        </a>
        <a href="./riwayat_tiket.php">
            <div class="item-navbar">
                <svg height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" id="fi_7327006"><g id="Layer_2" data-name="Layer 2"><path d="m14.673 22.89a2.475 2.475 0 0 1 -1.853-.849c-.319-.307-.463-.431-.813-.431s-.494.124-.814.431a2.449 2.449 0 0 1 -3.707 0c-.319-.308-.464-.432-.815-.432a.8.8 0 0 0 -.559.2 1.763 1.763 0 0 1 -1.878.221 1.726 1.726 0 0 1 -.984-1.57v-17.46a1.752 1.752 0 0 1 1.75-1.75h14a1.752 1.752 0 0 1 1.75 1.75v17.454a1.726 1.726 0 0 1 -.981 1.568 1.765 1.765 0 0 1 -1.881-.219.783.783 0 0 0 -.55-.193c-.351 0-.494.124-.814.431a2.472 2.472 0 0 1 -1.851.849zm-2.666-2.78a2.473 2.473 0 0 1 1.852.849c.32.307.463.431.814.431s.493-.124.812-.431a2.475 2.475 0 0 1 1.853-.849 2.269 2.269 0 0 1 1.485.52.269.269 0 0 0 .295.04.222.222 0 0 0 .132-.216v-17.454a.25.25 0 0 0 -.25-.25h-14a.25.25 0 0 0 -.25.25v17.458a.225.225 0 0 0 .133.218.27.27 0 0 0 .291-.039 2.273 2.273 0 0 1 1.5-.527 2.483 2.483 0 0 1 1.854.848c.32.308.464.432.816.432s.494-.124.813-.431a2.475 2.475 0 0 1 1.85-.849z"></path><path d="m16 6.75h-5.5a.75.75 0 0 1 0-1.5h5.5a.75.75 0 0 1 0 1.5z"></path><path d="m16 11.75h-5.5a.75.75 0 0 1 0-1.5h5.5a.75.75 0 0 1 0 1.5z"></path><path d="m16 16.75h-5.5a.75.75 0 0 1 0-1.5h5.5a.75.75 0 0 1 0 1.5z"></path><circle cx="8" cy="11" r="1"></circle><circle cx="8" cy="6" r="1"></circle><circle cx="8" cy="16" r="1"></circle></g></svg>
            </div>
        </a>
        <a href="./profile.php">
            <div class="item-navbar">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" id="fi_1144760"><path d="m437.019531 74.980469c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469s-132.667969 26.628906-181.019531 74.980469c-48.351563 48.351562-74.980469 112.640625-74.980469 181.019531s26.628906 132.667969 74.980469 181.019531c48.351562 48.351563 112.640625 74.980469 181.019531 74.980469s132.667969-26.628906 181.019531-74.980469c48.351563-48.351562 74.980469-112.640625 74.980469-181.019531s-26.628906-132.667969-74.980469-181.019531zm-325.914062 354.316406c8.453125-72.734375 70.988281-128.890625 144.894531-128.890625 38.960938 0 75.597656 15.179688 103.15625 42.734375 23.28125 23.285156 37.964844 53.6875 41.742188 86.152344-39.257813 32.878906-89.804688 52.707031-144.898438 52.707031s-105.636719-19.824219-144.894531-52.703125zm144.894531-159.789063c-42.871094 0-77.753906-34.882812-77.753906-77.753906 0-42.875 34.882812-77.753906 77.753906-77.753906s77.753906 34.878906 77.753906 77.753906c0 42.871094-34.882812 77.753906-77.753906 77.753906zm170.71875 134.425782c-7.644531-30.820313-23.585938-59.238282-46.351562-82.003906-18.4375-18.4375-40.25-32.269532-64.039063-40.9375 28.597656-19.394532 47.425781-52.160157 47.425781-89.238282 0-59.414062-48.339844-107.753906-107.753906-107.753906s-107.753906 48.339844-107.753906 107.753906c0 37.097656 18.84375 69.875 47.464844 89.265625-21.886719 7.976563-42.140626 20.308594-59.566407 36.542969-25.234375 23.5-42.757812 53.464844-50.882812 86.347656-34.410157-39.667968-55.261719-91.398437-55.261719-147.910156 0-124.617188 101.382812-226 226-226s226 101.382812 226 226c0 56.523438-20.859375 108.265625-55.28125 147.933594zm0 0"></path></svg>
            </div>
        </a>
    </div>


</body>
</html>

