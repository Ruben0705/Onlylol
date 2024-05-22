<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Onlylol</title>

  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/buttom.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/index.css" />
  <link rel="stylesheet" href="css/buscador.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>
<navbar>
  <div class="navbar">
    <div class="center-items">
      <a href="#">JUGABILIDAD</a>
      <a href="#">NOTICIAS</a>
      <a href="/champions">CAMPEONES</a>
    </div>

    <div class="login">
      <a href="login.php">LOGIN</a>
    </div>
    <a href="login.php"><img src="img/Perfil/champion_series_icon.png" alt="iconoPerfil" class="logoPerfil"></a>
  </div>
</navbar>

<body>
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
     <!-- <div class="buttom">
        <button class="continuar">
          <span>Continue</span>
          <svg
            width="34"
            height="34"
            viewBox="0 0 74 74"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <circle
              cx="37"
              cy="37"
              r="35.5"
              stroke="black"
              stroke-width="3"
            ></circle>
            <path
              d="M25 35.5C24.1716 35.5 23.5 36.1716 23.5 37C23.5 37.8284 24.1716 38.5 25 38.5V35.5ZM49.0607 38.0607C49.6464 37.4749 49.6464 36.5251 49.0607 35.9393L39.5147 26.3934C38.9289 25.8076 37.9792 25.8076 37.3934 26.3934C36.8076 26.9792 36.8076 27.9289 37.3934 28.5147L45.8787 37L37.3934 45.4853C36.8076 46.0711 36.8076 47.0208 37.3934 47.6066C37.9792 48.1924 38.9289 48.1924 39.5147 47.6066L49.0607 38.0607ZM25 38.5L48 38.5V35.5L25 35.5V38.5Z"
              fill="black"
            ></path>
          </svg>
        </button>
      </div>  -->
  </div>
  <script src="/js/script.js"></script>
</body>
</html>