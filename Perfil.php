<?php
session_start();

$patchNotesUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
$patchNotesResponse = file_get_contents($patchNotesUrl);
$patchNotes = json_decode($patchNotesResponse, true);
if (!$patchNotes) {
    die("Error al obtener la versión del parche.");
}
$currentPatchVersion = $patchNotes[0];

function getUserByUsername($username, $conn)
{
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function updateUsername($username, $new_username, $conn)
{
    $sql = "UPDATE users SET username = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_username, $username);
    return $stmt->execute();
}

function updatePassword($username, $new_password, $conn)
{
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt->bind_param("ss", $hashed_password, $username);
    return $stmt->execute();
}

function updateRiotAccount($username, $puuid, $conn)
{
    $sql = "UPDATE users SET riot_profile_id = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $puuid, $username);
    return $stmt->execute();
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "onlylol";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user = getUserByUsername($username, $conn);

$api_key = 'RGAPI-bfdf00f1-766f-4894-b88b-6b76c1725d7e';

$riot_profile = null;
if (!empty($user['riot_profile_id'])) {
    $puuid = $user['riot_profile_id'];
    $server = 'EUW1';
    $summoner_url = "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid?api_key=$api_key";
    $summoner_response = @file_get_contents($summoner_url);
    if ($summoner_response !== false) {
        $summoner_data = json_decode($summoner_response, true);
        if (isset($summoner_data['profileIconId'])) {
            $riot_profile = [
                'profileIconId' => $summoner_data['profileIconId'],
                'summonerLevel' => $summoner_data['summonerLevel']
            ];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['new_username'])) {
        $new_username = htmlspecialchars($_POST['new_username']);
        if (updateUsername($username, $new_username, $conn)) {
            $_SESSION['username'] = $new_username;
            $user = getUserByUsername($new_username, $conn);
            $success_message = "Nombre de usuario actualizado correctamente.";
        } else {
            $error_message = "Error al actualizar el nombre de usuario.";
        }
    }

    if (!empty($_POST['new_password'])) {
        $new_password = htmlspecialchars($_POST['new_password']);
        if (updatePassword($username, $new_password, $conn)) {
            $success_message = "Contraseña actualizada correctamente.";
        } else {
            $error_message = "Error al actualizar la contraseña.";
        }
    }

    if (!empty($_POST['username']) && !empty($_POST['hashtag']) && !empty($_POST['serv'])) {
        $riot_username = htmlspecialchars($_POST['username']);
        $riot_tag = htmlspecialchars($_POST['hashtag']);
        $server = htmlspecialchars($_POST['serv']);
        $riot_url = "https://americas.api.riotgames.com/riot/account/v1/accounts/by-riot-id/$riot_username/$riot_tag?api_key=$api_key";
        $response = @file_get_contents($riot_url);
        if ($response !== false) {
            $data = json_decode($response, true);
            if (isset($data['puuid'])) {
                $puuid = $data['puuid'];
                if (updateRiotAccount($username, $puuid, $conn)) {
                    $user = getUserByUsername($username, $conn);
                    $success_message = "Cuenta de Riot vinculada correctamente.";
                    $summoner_url = "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid?api_key=$api_key";
                    $summoner_response = @file_get_contents($summoner_url);
                    if ($summoner_response !== false) {
                        $summoner_data = json_decode($summoner_response, true);
                        if (isset($summoner_data['profileIconId'])) {
                            $riot_profile = [
                                'profileIconId' => $summoner_data['profileIconId'],
                                'summonerLevel' => $summoner_data['summonerLevel']
                            ];
                        }
                    }
                } else {
                    $error_message = "Error al vincular la cuenta de Riot.";
                }
            } else {
                $error_message = "No se encontró el perfil de Riot.";
            }
        } else {
            $error_message = "Error al obtener el perfil de Riot.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="public/css/Perfil.css">
    <link rel="stylesheet" href="public/css/Navbar.css">
    <link rel="stylesheet" href="public/css/Style.css">
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
    <div class="main-content">
        <div class="profile-container">
            <h1>Perfil de Usuario</h1>
            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <?php if ($riot_profile): ?>
                <div class="riot-profile">
                    <h2>Perfil de Riot</h2>
                    <img src="http://ddragon.leagueoflegends.com/cdn/<?php echo $currentPatchVersion ?>/img/profileicon/<?php echo htmlspecialchars($riot_profile['profileIconId']); ?>.png"
                        alt="Icono de Perfil">
                    <p><strong>Nivel de Invocador:</strong> <?php echo htmlspecialchars($riot_profile['summonerLevel']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="update-profile-section">
                <h2>Actualizar Perfil</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="new_username">Nuevo Nombre de Usuario:</label>
                    <input type="text" id="new_username" name="new_username"
                        value="<?php echo htmlspecialchars($user['username']); ?>">

                    <label for="new_password">Nueva Contraseña:</label>
                    <input type="password" id="new_password" name="new_password">

                    <label for="username">Nombre de Usuario de Riot:</label>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($user['riot_username'] ?? ''); ?>">

                    <label for="hashtag">Hashtag de Riot:</label>
                    <input type="text" id="hashtag" name="hashtag"
                        value="<?php echo htmlspecialchars($user['riot_tag'] ?? ''); ?>">

                    <label for="serv">Servidor:</label>
                    <select id="serv" name="serv" required>
                        <option value="EUW1">EUW1</option>
                        <option value="EUN1">EUN1</option>
                        <option value="BR1">BR1</option>
                        <option value="JP1">JP1</option>
                        <option value="KR">KR</option>
                        <option value="LA1">LA1</option>
                        <option value="LA2">LA2</option>
                        <option value="NA1">NA1</option>
                        <option value="OC1">OC1</option>
                        <option value="PH2">PH2</option>
                        <option value="RU">RU</option>
                        <option value="SG2">SG2</option>
                        <option value="TH2">TH2</option>
                        <option value="TR1">TR1</option>
                        <option value="TW2">TW2</option>
                        <option value="VN2">VN2</option>
                    </select>

                    <button type="submit">Guardar Cambios</button>
                </form>
            </div>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</body>

</html>