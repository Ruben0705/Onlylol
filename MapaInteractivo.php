<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Interactivo de League of Legends</title>
    <link rel="stylesheet" type="text/css" href="public/css/Map.css">
    <link rel="stylesheet" type="text/css" href="public/css/Navbar.css">
</head>

<body>
<header>
        <div class="navbar">
            <div class="logo"><a href="Index.php"><img src="img/Logo/onlylol.png"></a></div>
            <ul class="links">
                <li><a href="MapaInteractivo.php">MAPA INTERACTIVO</a></li>
                <li><a href="Champions.php">CAMPEONES</a></li>
                <li><a href="Players.php">JUGADORES</a></li>
            </ul>
            <div class="perfil-container">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="Perfil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <a href="logout.php" class="perfil">LOGOUT</a>
                <?php else: ?>
                    <a href="login.php" class="perfil">LOGIN</a>
                <?php endif; ?>
                <div class="logoPerfil"><a href="Login.php"><img src="img/Perfil/champion_series_icon.png"></a></div>
            </div>
            <div class="toggle_btn"><i class="fa-solid fa-bars"></i></div>
            <div class="dropdown_menu">
                <li><a href="MapaInteractivo.php">MAPA INTERACTIVO</a></li>
                <li><a href="Champions.php">CAMPEONES</a></li>
                <li><a href="Players.php">JUGADORES</a></li>
                <li><?php if (isset($_SESSION['username'])): ?>
                        <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        <a href="logout.php" class="perfilMenu">- LOGOUT</a>
                    <?php else: ?>
                        <a href="login.php" class="perfilMenu">- LOGIN</a>
                    <?php endif; ?>
                </li>
            </div>
        </div>
    </header>
    <script src="/public/js/Navbar.js"></script>
    <div class="container">
        <div class="pallette">
            <div class="width-buttons">
                <button id="thinWidthBtn">Fino</button>
                <button id="mediumWidthBtn">Medio</button>
                <button id="thickWidthBtn">Grueso</button>
            </div>
            <input type="color" id="colorPicker">
            <button id="clearBtn">Borrar</button>
        </div>
        <div id="map-container">
            <img id="map" src="img/Mapa/Map.jpg" alt="Mapa del League of Legends">
            <canvas id="drawingCanvas"></canvas>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
    <script src="public/js/Map.js"></script>
</body>

</html>