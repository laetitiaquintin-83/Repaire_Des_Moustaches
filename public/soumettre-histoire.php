<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getPDO();
$csrf_token = generateCSRFToken();
$message = '';
$error = '';

// Traiter le formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valider le token CSRF
    $csrf_check = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_check)) {
        $error = 'Erreur de sécurité : token CSRF invalide';
    } else {
        $titre = isset($_POST['titre']) ? trim((string) $_POST['titre']) : '';
        $contenu = isset($_POST['contenu']) ? trim((string) $_POST['contenu']) : '';
        $utilisateur_id = isset($_POST['utilisateur_id']) ? (int) $_POST['utilisateur_id'] : 0;

        // Validations
        if (empty($titre)) {
            $error = 'Le titre est obligatoire.';
        } elseif (empty($contenu)) {
            $error = 'L\'histoire est obligatoire.';
        } elseif ($utilisateur_id <= 0) {
            $error = 'Veuillez sélectionner un auteur.';
        } else {
            // Insérer l'histoire en base (statut "en_attente" pour modération)
            $sql = 'INSERT INTO belles_histoires (utilisateur_id, titre, contenu, statut, date_publication)
                    VALUES (:utilisateur_id, :titre, :contenu, "en_attente", NOW())';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':utilisateur_id' => $utilisateur_id,
                ':titre' => $titre,
                ':contenu' => $contenu,
            ]);
            
            $message = '✨ Votre histoire a été soumise ! Elle sera vérifiée avant publication.';
        }
    }
}

// Récupérer la liste des utilisateurs (adoptants)
$sqlUsers = 'SELECT id, nom, prenom FROM utilisateurs ORDER BY nom, prenom';
$utilisateurs = $pdo->query($sqlUsers)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre une histoire - Le Repaire des Moustaches</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        .formulaire-histoire {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .formulaire-histoire h2 {
            font-family: 'Pacifico', cursive;
            color: #FE7B7E;
            font-size: 2.2rem;
            font-weight: normal;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #2B2B2B;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #85D6CD;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            color: #2B2B2B;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #FE7B7E;
            box-shadow: 0 0 8px rgba(254, 123, 126, 0.2);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 250px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999;
        }

        .formulaire-histoire button {
            width: 100%;
            padding: 14px;
            background-color: #FE7B7E;
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.1rem;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .formulaire-histoire button:hover {
            background-color: #E55C5C;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(254, 123, 126, 0.3);
        }

        .message-succes {
            background-color: #E8F5E9;
            color: #2E7D32;
            border-left: 5px solid #4CAF50;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .message-erreur {
            background-color: #FFEBEE;
            color: #C62828;
            border-left: 5px solid #F44336;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .infos-importantes {
            background-color: #FFF8E7;
            border: 2px solid #85D6CD;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .infos-importantes h3 {
            color: #FE7B7E;
            margin-bottom: 10px;
        }

        .infos-importantes ul {
            margin-left: 20px;
        }

        .infos-importantes li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <main class="page-liste">
        <section class="liste-header">
            <h1>Soumettre une histoire</h1>
            <p>Partagez les aventures de votre chat adoptéet inspirez les autres!</p>
        </section>

        <div class="formulaire-histoire">
            <?php if (!empty($message)): ?>
                <div class="message-succes"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="message-erreur"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <div class="infos-importantes">
                <h3>📝 Avant de soumettre</h3>
                <ul>
                    <li>Votre histoire sera modérée avant d'être publiée sur le mur.</li>
                    <li>Respectez les valeurs du Repaire : bienveillance et solidarité.</li>
                    <li>Décrivez la vie quotidienne, les moments amusants, les progrès de votre chat.</li>
                    <li>Évitez les contenus offensants ou publicitaires.</li>
                </ul>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <div class="form-group">
                    <label for="utilisateur_id">Qui êtes-vous ? *</label>
                    <select id="utilisateur_id" name="utilisateur_id" required>
                        <option value="">-- Sélectionnez votre nom --</option>
                        <?php foreach ($utilisateurs as $user): ?>
                            <option value="<?php echo (int) $user['id']; ?>">
                                <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="titre">Titre de l'histoire *</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex: Velours découvre son nouveau jardin" required maxlength="150">
                </div>

                <div class="form-group">
                    <label for="contenu">L'histoire *</label>
                    <textarea id="contenu" name="contenu" placeholder="Racontez comment se passe la vie de votre chat adopté. Qu'aime-t-il faire? Y a-t-il des anecdotes amusantes?" required></textarea>
                </div>

                <button type="submit">Soumettre mon histoire</button>
            </form>
        </div>
    </main>
</body>
</html>
