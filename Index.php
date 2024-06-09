<?php
session_start();

if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
  $_SESSION['username'] = $_COOKIE['username'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cookie-consent'])) {
  $consent = $_POST['cookie-consent'] === 'accept';
  setcookie('cookie-consent', $consent ? 'accepted' : 'denied', time() + (86400 * 30), "/"); // 30 días
  header("Location: " . $_SERVER['REQUEST_URI']);
  exit();
}

$cookieConsent = isset($_COOKIE['cookie-consent']) ? $_COOKIE['cookie-consent'] : null;
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Onlylol</title>
  <link rel="stylesheet" href="public/css/Cookies.css" />
  <link rel="stylesheet" href="public/css/Style.css" />
  <link rel="stylesheet" href="public/css/Navbar.css" />
  <link rel="stylesheet" href="public/css/Index.css" />
  <link rel="stylesheet" href="public/css/Buscador.css" />
  <link rel="shortcut icon" href="/img/onlylol.png">
  <link rel="icon" href="/img/Logo/onlylol.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<?php if ($cookieConsent === null): ?>
  <div class="cookie-banner">
    <p>Este sitio web utiliza cookies para mejorar su experiencia. ¿Acepta el uso de cookies?</p>
    <form method="post" action="">
      <button type="submit" name="cookie-consent" value="accept">Aceptar</button>
      <button type="submit" name="cookie-consent" value="deny">Denegar</button>
    </form>
  </div>
<?php endif; ?>

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

  <div class="presentacion-container">
    <div class="presentacion">
      <span class="title"><img src="img/Logo/onlylol.png" alt="Logo de onlylol" class="logo-title">nlyLoL<br></span>
      <h2>Todo lo que necesitas acerca de League of Legends</h2>
      <div class="acordeon-container">

        <div class="acordeon" id="border-top">
          <input type="radio" name="acordeon" id="btn-acordeon1" class="btn-acordeon">
          <label for="btn-acordeon1">Diseña tu estrategia</label>
          <div class="contenido-acordeon">
            <p>Dibuja tu estrategia directamente en el mapa.</p>
            <a href="MapaInteractivo.php">Ir al Mapa Interactivo</a>
          </div>
        </div>

        <div class="acordeon">
          <input type="radio" name="acordeon" id="btn-acordeon2" class="btn-acordeon">
          <label for="btn-acordeon2">Información de campeones</label>
          <div class="contenido-acordeon">
            <p>Busca aquí la mejor información de tus campeones.</p>
            <a href="Champions.php">Ir a toda la información de campeones</a>
          </div>
        </div>

        <div class="acordeon">
          <input type="radio" name="acordeon" id="btn-acordeon3" class="btn-acordeon">
          <label for="btn-acordeon3">Buscador de jugadores</label>
          <div class="contenido-acordeon">
            <p>¿Buscas el perfil de alguien? Haz clic aquí y busca a quién quieras.</p>
            <a href="Players.php">Ir a búsqueda de Jugadores</a>
          </div>
        </div>

        <div class="acordeon" id="border-bottom">
          <input type="radio" name="acordeon" id="btn-acordeon4" class="btn-acordeon">
          <label for="btn-acordeon4">Inicia sesión o regístrate</label>
          <div class="contenido-acordeon">
            <p>Inicia sesión si tienes cuenta, o crea una en un clic.</p>
            <a href="Login.php">Ir al inicio de sesión</a>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
<script>
  document.cookie = "testcookie=1";
  let cookiesEnabled = document.cookie.indexOf("testcookie=") != -1;
  if (cookiesEnabled) {
    document.cookie = "testcookie=1; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  } else {
    alert("Las cookies están deshabilitadas en su navegador.");
  }
  window.onload = function () {
    if (document.cookie.indexOf("cookie-consent=accepted") !== -1 || document.cookie.indexOf("cookie-consent=denied") !== -1) {
      document.getElementById("cookie-banner").style.display = "none";
    }
  };
</script>

</html>