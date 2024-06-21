<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css">
</head>
<body>
    <div class="container">
        <div class="content">
            <img id="logo-kai" src="./assets/512px-Logo_PT_Kereta_Api_Indonesia_(Persero)_2020 1.png">

            <form action="./function/login.php" method="post">
                <label>Email</label>
                <input class="input-form" type="text" name="email" required>
                <label>Password</label>
                <input class="input-form" type="password" name="password" required>
                
                <?php
                    session_start();
                    if (isset($_SESSION['error'])) {
                        echo '<p style="color:red; text-align: center;" >' . $_SESSION['error'] . '</p>';
                        unset($_SESSION['error']);
                    }
                ?>

                <div class="button-login">
                    <div class="item">
                        <input type="submit" value="Masuk">
                    </div>
                    <p>Belum punya akun?</p>
                    <div class="item signup-button">
                        <a href="./signup.html"><p>Daftar</p></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="circle">
        <div class="large-circle"></div>
        <div class="small-circle"></div>
    </div>
</body>
</html>
