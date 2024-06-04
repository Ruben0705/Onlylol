<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameHashtag = $_POST['usernameHashtag'];
    $region = $_POST['region'];
    list($username, $hashtag) = explode('#', $usernameHashtag);

    $api_key = 'RGAPI-31a8fa7d-29e0-4501-bce9-0f8563cb806b';

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

        // Realizar la solicitud a la API
        $response = @file_get_contents($url); // Usar '@' para suprimir errores de file_get_contents
        if ($response !== false) {
            $data = json_decode($response, true);

            // Verificar si se encontraron resultados
            if (isset($data['puuid'])) {
                $puuid = $data['puuid'];

                // Solicitar información adicional del jugador (nivel, imagen de perfil, etc.)
                $summoner_url = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid?api_key=$api_key";
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

                        // Obtener la maestría de campeones
                        $mastery_url = "https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/{$summoner_data['id']}?api_key=$api_key";
                        $mastery_response = @file_get_contents($mastery_url);
                        if ($mastery_response !== false) {
                            $mastery_data = json_decode($mastery_response, true);
                            usort($mastery_data, function ($a, $b) {
                                return $b['championPoints'] - $a['championPoints'];
                            });
                            $top_champions = array_slice($mastery_data, 0, 3);

                            // Obtener los nombres de los campeones
                            $champion_data = @file_get_contents("http://ddragon.leagueoflegends.com/cdn/11.1.1/data/en_US/champion.json");
                            if ($champion_data !== false) {
                                $champion_data = json_decode($champion_data, true)['data'];
                                foreach ($top_champions as &$champion) {
                                    $champion_id = $champion['championId'];
                                    foreach ($champion_data as $name => $details) {
                                        if ($details['key'] == $champion_id) {
                                            $champion['championName'] = $details['name'];
                                            $champion['championImage'] = "http://ddragon.leagueoflegends.com/cdn/11.1.1/img/champion/{$details['image']['full']}";
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $error = "Hubo un error al obtener la información de maestría de campeones. URL: $mastery_url";
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
            $error = "Hubo un error al realizar la solicitud a la API de la región '$region'. URL: $url";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Perfil de Usuario</title>
    <link rel="stylesheet" href="public/css/Players.css">
</head>
<body>
    <h1>Buscar Perfil de Usuario</h1>
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
        <button type="submit">Buscar</button>
    </form>

    <?php if (isset($profile)): ?>
        <h2>Perfil Encontrado</h2>
        <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($profile['username']); ?></p>
        <p><strong>Tagline:</strong> <?php echo htmlspecialchars($profile['hashtag']); ?></p>
        <p><strong>Nivel:</strong> <?php echo htmlspecialchars($profile['summonerLevel']); ?></p>
        <p><strong>Imagen de Perfil:</strong></p>
        <img src="http://ddragon.leagueoflegends.com/cdn/14.10.1/img/profileicon/<?php echo htmlspecialchars($profile['profileIconId']); ?>.png" alt="Icono de Perfil">
        <?php if (isset($top_champions)): ?>
            <h3>Top 3 Campeones con Más Maestría</h3>
            <ul>
                <?php foreach ($top_champions as $champion): ?>
                    <li>
                        <p>Nombre del Campeón: <?php echo htmlspecialchars($champion['championName']); ?></p>
                        <p>Puntos de Maestría: <?php echo htmlspecialchars($champion['championPoints']); ?></p>
                        <img src="<?php echo htmlspecialchars($champion['championImage']); ?>" alt="<?php echo htmlspecialchars($champion['championName']); ?>">
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php elseif (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <div id="cookieConsent">
        <div class="cookie-container">
            <p>Esta página utiliza cookies para mejorar la experiencia del usuario. ¿Aceptas el uso de cookies?</p>
            <button onclick="setCookie('accept')">Aceptar</button>
            <button onclick="setCookie('decline')">Rechazar</button>
        </div>
    </div>

    <script>
        function setCookie(decision) {
            document.cookie = "cookieConsent=" + decision + "; path=/; max-age=" + (60 * 60 * 24 * 30); // 30 días
            document.getElementById('cookieConsent').style.display = 'none';
        }

        window.onload = function() {
            if (document.cookie.split(';').some((item) => item.trim().startsWith('cookieConsent='))) {
                document.getElementById('cookieConsent').style.display = 'none';
            }
        };
    </script>
</body>
</html>
