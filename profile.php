<?php
session_start();
include './includes/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user information
$sql = "SELECT * FROM pengguna WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Fetch profile picture
$sql = "SELECT * FROM gambar_pengguna WHERE kd = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user['kd_gambar']);
$stmt->execute();
$result = $stmt->get_result();
$gambar = $result->fetch_assoc();

$profile_picture = $gambar ? 'data:image/jpeg;base64,' . base64_encode($gambar['file']) : './assets/default_profile.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['nama']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css">
    <style>
        .box-form {
            margin-bottom: 0;
        }
        .profile-gambar img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        .unggah {
            cursor: pointer;
        }
        #konfirmasi-pesan{
            display: flex;
            height: 40px;
            align-items: center;
            color: white;
            font-size: 1rem;
            border: 0;
            
        }
    </style>
</head>
<body>
    <div class="search-container">
        <div class="search">
            <div class="pesan-title">
                <a href="./dashboard.php">
                    <svg id="fi_9312240" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <g>
                                <path d="m411.5 281h-298c-13.81 0-25-11.19-25-25s11.19-25 25-25h298c13.81 0 25 11.19 25 25s-11.19 25-25 25z"></path>
                            </g>
                            <g>
                                <path d="m227.99 399.25c-6.08 0-12.18-2.21-16.99-6.67l-127.5-118.25c-5.14-4.77-8.05-11.48-8-18.5.05-7.01 3.04-13.69 8.25-18.39l131-118.25c10.25-9.25 26.06-8.44 35.31 1.81s8.44 26.06-1.81 35.31l-110.72 99.94 107.47 99.67c10.12 9.39 10.72 25.21 1.33 35.33-4.93 5.31-11.62 8-18.34 8z"></path>
                            </g>
                        </g>
                    </svg>
                </a>
                <h1>Profile</h1>
                <div class="spacer"></div>
            </div>
        </div>
        <div class="text-dashboard">
            <p>Detail Profile</p>
        </div>
    </div>

    <div class="blue-background-img">
        <img id="background-img" src="./assets/image.jpeg">
    </div>

    <div class="container">
        <div class="content">
            <div class="profile-gambar">
                <img id="gambarmu" src="<?php echo $profile_picture; ?>" alt="Profile Picture">
                <div class="unggah" id="edit-button">
                    <svg height="492pt" viewBox="0 0 492.49284 492" width="492pt" xmlns="http://www.w3.org/2000/svg" id="fi_1828911">
                        <path d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0"></path>
                        <path d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="container-pesan">
        <form id="profile-form" action="./function/update_profile.php" method="POST" enctype="multipart/form-data">
            <label class="first-form">Email:</label>
            <input class="box-form" type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly><br>
            <label>Nama:</label>
            <input class="box-form" type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" readonly><br>
            <label>Jenis Kelamin:</label>
            <input class="box-form" type="text" name="sex" value="<?php echo htmlspecialchars($user['sex']); ?>" readonly><br>
            <label>Tanggal Lahir:</label>
            <input class="box-form" type="date" name="tgl_lahir" value="<?php echo htmlspecialchars($user['tgl_lahir']); ?>" readonly><br>
            <label>No Identitas:</label>
            <input class="box-form" type="text" name="no_identitas" value="<?php echo htmlspecialchars($user['no_identitas']); ?>" readonly><br>
            
            <label id="label-profile-picture" style="display: none;">Gambar Profil:</label>
            <input type="file" name="profile_picture" id="profile_picture" style="display: none;" disabled><br>
            <button id="save-button" style="display: none; margin-bottom: 2rem;" type="submit">Save</button>
            <a id="konfirmasi-pesan" href="./dashboard.php">
                Dashboard
            </a>
        </form>
    </div>

    <script>
        document.getElementById('edit-button').addEventListener('click', function() {
            document.querySelectorAll('#profile-form input').forEach(input => input.removeAttribute('readonly'));
            document.getElementById('profile_picture').removeAttribute('disabled');
            document.getElementById('profile_picture').style.display = 'block';
            document.getElementById('label-profile-picture').style.display = 'block';
            document.getElementById('edit-button').style.display = 'none';
            document.getElementById('save-button').style.display = 'inline';
            document.getElementById('konfirmasi-pesan').style.display = 'none';
        });
    </script>
</body>
</html>
