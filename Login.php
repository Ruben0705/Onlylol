<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Onlylol</title>
    <link rel="stylesheet" href="public/css/Login.css" />
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-top">
                <a href="index.php">
                    <img src="https://login-lol.netlify.app/assets/logo.svg" width="40" alt="Logo League of Legends" />
                </a>
                <div class="language">
                    <p>ES(EU)</p>
                    <i id="globe">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Globe_icon_2.svg/800px-Globe_icon_2.svg.png"
                            width="20" />
                    </i>
                </div>
            </div>
            <div class="login-body">
                <p>LOGIN</p>

                <form method="POST" action="Autenticacion.php" onkeyup="check()" id="login" autocomplete="off">
                    <input type="text" name="login" placeholder="Correo electrónico o Nombre de Usuario" required />
                    <input type="password" name="password" placeholder="Contraseña" required />

                    <div class="checkbox">
                        <input type="checkbox" name="keep" id="keep" />
                        <label for="keep">No olvidar</label>
                    </div>

                    <div class="button">
                        <button type="submit" disabled>&#x279C;</button>
                    </div>
                </form>
            </div>
            <div class="login-bottom">
                <a href="Registro.php">Crear cuenta</a>
            </div>
        </div>

        <div class="background-container">
            <div class="background-alert"></div>
        </div>
    </div>

    <script>
        function check() {
            form = document.getElementById("login");
            let login = form.children[0].value;
            let password = form.children[1].value;
            let button = form.children[3].children[0];

            if (login.length > 0 && password.length > 0) {
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