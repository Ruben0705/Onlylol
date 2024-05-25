<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campeón - Onlylol</title>
    <link rel="stylesheet" href="public/css/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
