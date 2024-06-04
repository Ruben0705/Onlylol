<?php
function getUserByUsername($username, $conn) {
    $sql = "SELECT * FROM users WHERE username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return $user;
    } else {
        return null; 
    }
}

$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_de_datos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$username = "nombre_de_usuario"; 
$user = getUserByUsername($username, $conn);

if ($user !== null) {
    echo "Nombre de usuario: " . $user['username'] . "<br>";
    echo "Correo electrónico: " . $user['email'] . "<br>";
} else {
    echo "El usuario no fue encontrado en la base de datos.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
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
  
    <h1>Perfil de Usuario</h1>
    <p><strong>Nombre de usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <?php if ($riot_profile_info): ?>
        <h2>Perfil de Riot</h2>
        <p><strong>Nombre de usuario de Riot:</strong> <?php echo htmlspecialchars($riot_profile_info['riot_username']); ?></p>
    <?php endif; ?>
    <h2>Cambiar Nombre de Usuario o Contraseña</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_username">Nuevo Nombre de Usuario:</label>
        <input type="text" id="new_username" name="new_username" required>
        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="logout.php">Cerrar Sesión
