<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campeón - Onlylol</title>
    <link rel="stylesheet" href="public/css/Navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <header>
    <div class="navbar">
      <div class="logo"><a href="Index.php"><img src="img/Logo/onlylol.png"></a></div>
      <ul class="links">
        <li><a href="#">JUGABILIDAD</a></li>
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
        <li><a href="#">JUGABILIDAD</a></li>
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
        <main id="champion-details">
            <?php
            if (isset($_GET['champion'])) {
                $championId = htmlspecialchars($_GET['champion'], ENT_QUOTES, 'UTF-8');
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        fetch('https://ddragon.leagueoflegends.com/cdn/14.10.1/data/es_ES/champion.json')
                            .then(response => response.json())
                            .then(data => {
                                const champion = data.data['$championId'];
                                const championDetailsDiv = document.getElementById('champion-details');
                                championDetailsDiv.innerHTML = `
                                    <h2>\${champion.name}</h2>
                                    <p>\${champion.title}</p>
                                    <img src='https://ddragon.leagueoflegends.com/cdn/img/champion/splash/\${champion.id}_0.jpg' alt='\${champion.name}' style='width:100%;'>
                                    <div class='champion-detail'>
                                        <strong>Rol:</strong> \${champion.tags.join(', ')}
                                    </div>
                                    <div class='champion-detail'>
                                        <strong>Descripción:</strong> \${champion.blurb}
                                    </div>
                                `;
                            });
                    });
                </script>";
            } else {
                echo "<p>No se ha seleccionado ningún campeón.</p>";
            }
            ?>
        </main>
    </div>
</body>
</html>
