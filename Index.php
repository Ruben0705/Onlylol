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

  <link rel="stylesheet" href="public/css/Style.css" />
  <link rel="stylesheet" href="public/css/Navbar.css" />
  <link rel="stylesheet" href="public/css/Index.css" />
  <link rel="stylesheet" href="public/css/Buscador.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="shortcut icon" href="/img/onlylol.png">
  <link rel="icon" href="/img/Logo/onlylol.png">
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
  <navbar>
    <a href="Index.php" class="logoOnlylol"><img src="img/Logo/onlylol.png" alt="Logo de onlylol" class="Onlylol"></a>
    <div class="navbar">
      <div class="center-items">
        <a href="#">JUGABILIDAD</a>
        <a href="Players.php">JUGADORES</a>
        <a href="Champions.php">CAMPEONES</a>
      </div>

      <div class="login">
        <?php if (isset($_SESSION['username'])): ?>
          <a href="Perfil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
          <a href="logout.php">LOGOUT</a>
        <?php else: ?>
          <a href="login.php">LOGIN</a>
        <?php endif; ?>
      </div>
      <a href="Perfil.php">
        <img src="img/Perfil/champion_series_icon.png" alt="iconoPerfil" class="logoPerfil">
      </a>
    </div>
  </navbar>
  <div class="presentacion-container">
    <div class="presentacion">
     <span class="title"><img src="img/Logo/onlylol.png" alt="Logo de onlylol" class="logo-title">nlyLoL<br></span>
      <span class="subtitle">
        Busca aquí la mejor información de tus campeones 
      </span>
      <div id="search-container" class="search-container">
        <input type="text" id="search-input" placeholder="Buscar...">
        <div id="results"></div>
      </div>
    </div>
  </div>
</body>
<script src="/public/js/Champions.js"></script>
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
<style>
  .cookie-banner {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 0;
    z-index: 1000;
  }

  .cookie-banner p {
    margin: 0;
    padding: 0 10px;
    display: inline-block;
  }

  .cookie-banner form {
    display: inline-block;
    margin: 0 5px;
  }

  .cookie-banner button {
    background-color: #fff;
    color: #333;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    margin: 0 5px;
  }

  .cookie-banner button:hover {
    background-color: #ddd;
  }
</style>

</html>