<?php
session_start();

$patchNotesUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
$patchNotesResponse = file_get_contents($patchNotesUrl);
$patchNotes = json_decode($patchNotesResponse, true);
$currentPatchVersion = $patchNotes[0];
$serv = 'EUW1';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameHashtag = $_POST['usernameHashtag'];
    $region = $_POST['region'];
    $serv = $_POST['serv'];
    list($username, $hashtag) = explode('#', $usernameHashtag);
    error_reporting(0);

    $api_key = 'RGAPI-bfdf00f1-766f-4894-b88b-6b76c1725d7e';

    $regionUrls = [
        'americas' => 'americas.api.riotgames.com',
        'europe' => 'europe.api.riotgames.com',
        'asia' => 'asia.api.riotgames.com',
        'esports' => 'esports.api.riotgames.com',
    ];

    if (!isset($regionUrls[$region])) {
        $error = "La región seleccionada no es válida.";
    } else {
        $base_url = $regionUrls[$region];

        $url = "https://$base_url/riot/account/v1/accounts/by-riot-id/$username/$hashtag?api_key=$api_key";


        $response = @file_get_contents($url);
        if ($response !== false) {
            $data = json_decode($response, true);

            if (isset($data['puuid'])) {
                $puuid = $data['puuid'];

                $summoner_url = "https://$serv.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid?api_key=$api_key";
                $summoner_response = @file_get_contents($summoner_url);
                if ($summoner_response !== false) {
                    $summoner_data = json_decode($summoner_response, true);
                    if (isset($summoner_data['profileIconId'])) {
                        $profile = [
                            'username' => $username,
                            'hashtag' => $hashtag,
                            'profileIconId' => $summoner_data['profileIconId'],
                            'summonerLevel' => $summoner_data['summonerLevel']
                        ];

                        $mastery_url = "https://$serv.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/{$summoner_data['id']}?api_key=$api_key";
                        $mastery_response = @file_get_contents($mastery_url);
                        if ($mastery_response !== false) {
                            $mastery_data = json_decode($mastery_response, true);
                            usort($mastery_data, function ($a, $b) {
                                return $b['championPoints'] - $a['championPoints'];
                            });
                            $top_champions = array_slice($mastery_data, 0, 3);

                            $champion_data = @file_get_contents("http://ddragon.leagueoflegends.com/cdn/{$currentPatchVersion}/data/en_US/champion.json");
                            if ($champion_data !== false) {
                                $champion_data = json_decode($champion_data, true)['data'];
                                foreach ($top_champions as &$champion) {
                                    $champion_id = $champion['championId'];
                                    foreach ($champion_data as $name => $details) {
                                        if ($details['key'] == $champion_id) {
                                            $champion['championName'] = $details['name'];
                                            $champion['championImage'] = "http://ddragon.leagueoflegends.com/cdn/{$currentPatchVersion}/img/champion/{$details['image']['full']}";
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $error = "Hubo un error al obtener la información de maestría de campeones.";
                        }
                    } else {
                        $error = "No se pudo obtener la imagen de perfil y el nivel del jugador.";
                    }
                } else {
                    $error = "Hubo un error al obtener la información adicional del jugador. URL: $summoner_url";
                }
            } else {
                $error = "No se encontraron resultados para el nombre de usuario '$username' y hashtag '$hashtag'.";
            }
        } else {
            $error = "Hubo un error al realizar la solicitud de la región";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar jugadores</title>
    <link rel="stylesheet" href="public/css/Player.css">
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="usernameHashtag">Nombre de Usuario y Hashtag:</label>
            <input type="text" id="usernameHashtag" name="usernameHashtag" placeholder="Usuario#Hashtag" required>
            <label for="region">Región:</label>
            <select id="region" name="region" required>
                <option value="europe">Europa</option>
                <option value="americas">Américas</option>
                <option value="asia">Asia</option>
                <option value="esports">Esports</option>
            </select>
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
                <option value="TR2">TR1</option>
                <option value="TW2">TW1</option>
                <option value="VN2">VN2</option>
            </select>
            <button type="submit">Buscar</button>
        </form>

        <?php if (isset($profile)): ?>
            <h2>Perfil Encontrado</h2>
            <p class="userName"><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($profile['username']); ?>
            </p>
            <p class="tag"><strong>Tagline:</strong> <?php echo htmlspecialchars($profile['hashtag']); ?></p>
            <p class="lvl"><strong>Nivel:</strong> <?php echo htmlspecialchars($profile['summonerLevel']); ?></p>
            <img src="http://ddragon.leagueoflegends.com/cdn/<?php echo $currentPatchVersion ?>/img/profileicon/<?php echo htmlspecialchars($profile['profileIconId']); ?>.png"
                alt="Icono de Perfil" class="iconPerf">
            <?php if (isset($top_champions)): ?>
                <h3>Top 3 Campeones con Más Maestría</h3>
                <ul>
                    <?php foreach ($top_champions as $champion): ?>
                        <li>
                            <p>Nombre del Campeón: <?php echo htmlspecialchars($champion['championName']); ?></p>
                            <p>Puntos de Maestría: <?php echo htmlspecialchars($champion['championPoints']); ?></p>
                            <img src="<?php echo htmlspecialchars($champion['championImage']); ?>"
                                alt="<?php echo htmlspecialchars($champion['championName']); ?>">
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php elseif (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>

</html>