<?php
// On indique au navigateur le vrai code HTTP 404
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page introuvable | Le Repaire des Moustaches</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #FFFDF9; /* Ton fond crème */
            color: #2D2D2D;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            text-align: center;
            padding: 20px;
        }
        img {
            max-width: 300px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        h1 { font-family: 'Pacifico', cursive; color: #E05D52; font-size: 2.5rem; }
        p { font-size: 1.1rem; margin-bottom: 25px; }
        .btn {
            background-color: #E05D52;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn:hover { background-color: #C04238; }
    </style>
</head>
<body>

    <!-- Ton image du chat dans le carton -->
   <!-- Le '/' au début force le navigateur à chercher à la racine du site -->
<img src="images/404.jpg" alt="404 Cat Not Found">
    <h1>Oups ! Chat introuvable...</h1>
    <p>La page que vous cherchez a probablement été cachée par l'un de nos pensionnaires.</p>

    <a href="/index.php" class="btn">Retourner à la page d'accueil</a>

</body>
</html>