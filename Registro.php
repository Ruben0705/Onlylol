<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlylol";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "SELECT id FROM users WHERE email='$email' OR username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "El correo o el nombre de usuario ya existen.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;
            setcookie("username", $username, time() + (86400 * 30), "/"); // 86400 = 1 día
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Onlylol - Registro</title>
    <link rel="stylesheet" href="public/css/Login.css" />
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-top">
                <a href="index.php">
                    <img src="img/Logo/onlylol.png" width="40" alt="Logo League of Legends" />
                </a>
            </div>
            <div class="login-body">
                <p>REGISTRO</p>

                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST" onkeyup="check()" id="register" autocomplete="off">
                    <input type="text" name="username" placeholder="Nombre de Usuario" required />
                    <input type="email" name="email" placeholder="Correo electrónico" required />
                    <input type="password" name="password" placeholder="Contraseña" required />

                    <div class="button">
                        <button type="submit" disabled>&#x279C;</button>
                    </div>
                </form>
            </div>
            <div class="login-bottom">
                <a href="login.php">Ya tienes una cuenta? Inicia sesión</a>
            </div>
        </div>

        <div class="background-container">
            <div class="background-alert"></div>
        </div>
    </div>

    <script>
        function check() {
            form = document.getElementById("register");
            let username = form.children[0].value;
            let email = form.children[1].value;
            let password = form.children[2].value;
            let button = form.children[3].children[0];

            if (username.length > 0 && email.length > 0 && password.length > 0) {
                button.classList.add("btn-active");
                button.disabled = false;
            } else {
                button.classList.remove("btn-active");
                button.disabled = true;
            }
        }
    </script>
</body>
</html>
