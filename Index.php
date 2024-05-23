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
</head>

<body>
  <navbar>
    <a href="Index.php" class="logoOnlylol"><img src="/img/Logo/onlylol.png" alt="Logo de onlylol" class="Onlylol"></a>
    <div class="navbar">
      <div class="center-items">
        <a href="#">JUGABILIDAD</a>
        <a href="#">NOTICIAS</a>
        <a href="Champions.php">CAMPEONES</a>
      </div>

      <div class="login">
        <a href="login.php">LOGIN</a>
      </div>
      <a href="login.php"><img src="img/Perfil/champion_series_icon.png" alt="iconoPerfil" class="logoPerfil"></a>
    </div>
  </navbar>
  <div class="presentacion">
    <span class="title"> ONLYLOL </span>
    <span class="subtitle">
      Donde buscar las mejor informacion de tus campeones
    </span>
    <div id="search-container">
      <select id="region-select">
        <option value="na1">North America</option>
        <option value="euw1">Europe West</option>
        <option value="eun1">Europe Nordic & East</option>
        <!-- Añade más regiones según sea necesario -->
      </select>
      <input type="text" id="search-input" placeholder="Buscar...">
      <div id="results"></div>
    </div>
  </div>
</body>
  <script src="/public/js/Champions.js"></script>
</html>