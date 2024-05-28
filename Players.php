<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $hashtag = $_POST['hashtag'];
    $region = $_POST['region'];

    $regionUrls = [
        'Americas' => 'americas.api.riotgames.com',
        'Europa' => 'europe.api.riotgames.com',
        'Esport' => 'esports.api.riotgames.com',
        'Asia' => 'asia.api.riotgames.com'
    ];

    if (isset($regionUrls[$region])) {
        $regionUrl = $regionUrls[$region];
    } else {
        $error = "La región '$region' no tiene una URL asociada.";
    }

    if (!isset($error)) {
        $api_key = 'RGAPI-b821746e-bd59-459a-af6f-2f597eed3f11';
        $url = "https://$regionUrl/riot/account/v1/accounts/by-riot-id/$username/$hashtag?api_key=$api_key";


        $response = @file_get_contents($url);
        if ($response !== false) {
            $data = json_decode($response, true);

            if (isset($data['puuid'])) {
                $profile = $data;
            } else {
                $error = "No se encontraron resultados para el nombre de usuario '$username' en la región '$region'.";
            }
        } else {
            $error = "Hubo un error al realizar la solicitud a la API de la región '$region'.";
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
</head>

<body>
    <h1>Buscar Perfil de Usuario</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="hashtag">Hashtag:</label>
        <input type="text" id="hashtag" name="hashtag" required>
        <p>Introduce el hashtag asociado al usuario.</p>

        <label for="region">Región:</label>
        <select name="region" id="region" required>
            <option value="Americas">Americas</option>
            <option value="Europa">Europa</option>
            <option value="Esport">Esport</option>
            <option value="Asia">Asia</option>
        </select>
        <p>Selecciona la región correspondiente al usuario.</p>

        <button type="submit">Buscar</button>
    </form>

    <?php if (isset($profile)): ?>
        <h2>Perfil Encontrado</h2>
        <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($profile['gameName']); ?></p>
        <p><strong>Tagline:</strong> <?php echo htmlspecialchars($profile['tagLine']); ?></p>
    <?php elseif (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
</body>

</html>