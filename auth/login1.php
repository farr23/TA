<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Qita</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="login">
            <form action="loginproses.php" method="post">
                <h1>Login</h1>
                <hr>
                <p>Cafe Qita Malang</p>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
                <button type="submit" class="btn" name="loginbtn">Login</button>
                <p>
                    <a href="register.php">Belum punya akun?</a>
                </p>
            </form>
            <?php
            if (isset($_GET['error'])) {
                echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
            ?>
        </div>
        <div class="left">
            <img src="..asset/image/cq.png" alt="">
        </div>
    </div>
</body>
</html>