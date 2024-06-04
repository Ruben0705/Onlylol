<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onlylol</title>
    <link rel="stylesheet" href="public/css/Navbar.css">
    <link rel="stylesheet" href="public/css/Champions.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
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
          <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
          <a href="logout.php">LOGOUT</a>
        <?php else: ?>
          <a href="login.php">LOGIN</a>
        <?php endif; ?>
      </div>
      <a href="profile.php">
        <img src="img/Perfil/champion_series_icon.png" alt="iconoPerfil" class="logoPerfil">
      </a>
    </div>
  </navbar>
    <div id="content">
        <aside id="champion-list">
            <input type="text" id="search" placeholder="Buscar Campeón">
            <div id="champions"></div>
        </aside>
        <main id="champion-details">        
        </main>
    </div>
    <script src="public/js/Champions.js"></script>
</body>
</html>
