<?php
session_start();

$patchNotesUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
$patchNotesResponse = file_get_contents($patchNotesUrl);
$patchNotes = json_decode($patchNotesResponse, true);
if (!$patchNotes) {
  die("Error al obtener la versión del parche.");
}
$currentPatchVersion = $patchNotes[0];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onlylol</title>
  <link rel="stylesheet" href="public/css/Navbar.css">
  <link rel="stylesheet" href="public/css/Champions.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <header>
    <div class="navbar">
      <div class="logo"><a href="Index.php"><img src="img/Logo/onlylol.png"></a></div>
      <ul class="links">
        <li><a href="NotasParche.php">NOTAS DEL PARCHE</a></li>
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
        <li><a href="NotasParche.php">NOTAS DEL PARCHE</a></li>
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

  <div id="content">
    <aside id="champion-list">
      <input type="text" id="search" placeholder="Buscar Campeón">
      <div id="champions"></div>
    </aside>
    <main id="champion-details">
    </main>
  </div>
  <script>
    const currentPatchVersion = "<?php echo $currentPatchVersion; ?>";
  </script>
  <script src="public/js/Champions.js"></script>
</body>

</html>