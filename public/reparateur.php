<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
echo "<h2>🧹 Nettoyage de la boutique et du panier...</h2>";

$dossier = __DIR__;

if (is_dir($dossier)) {
    $fichiers = glob($dossier . '/*.php');
    foreach ($fichiers as $fichier) {
        if (basename($fichier) === 'reparateur.php') continue;
        
        $contenu = file_get_contents($fichier);
        
        // --- LE DICTIONNAIRE SPÉCIAL PANIER ---
        $dictionnaire = [
            // Nettoyage de la devise Euro brisée
            'â‚¬'    => '€',          // Pour réparer les prix comme "5,99 â‚¬" -> "5,99 €"
            'â,¬'    => '€',          // Variante d'affichage Windows-1252
            
            // Émojis et flèches du panier
            'â†»'    => '🔄',         // Bouton de mise à jour de quantité
            'â€•ï¸'  => '🗑️',         // Icône de suppression / poubelle
            'â€'    => '',           // Caractère invisible parasite
            'ï¸'    => '',           // Caractère invisible parasite d'émoji
            
            // Les précédents bugs détectés
            'àª'     => 'ê',
            'â†'    => '←',
            '¾'      => '🐾',
            'dà®ner' => 'dîner',
            'dà®'    => 'dî',
            'à®'     => 'î',
            'oà¹'    => 'où',
            'à¹'     => 'ù',
            'à‰té'   => 'Été',
            'à‰'     => 'É',
            'à€'     => 'À',
            'âœ¨'    => '✨',
            'âœ'     => 'œ',
            'à©'     => 'é',
            'à¨'     => 'è',
            'à§'     => 'ç',
            'à '     => 'à',
            
            // Standards doublés
            'Ã©'     => 'é',
            'Ã¨'     => 'è',
            'Ã '     => 'à',
            'Ã§'     => 'ç',
            'Ã¹'     => 'ù',
            'Ã»'     => 'û',
            'Ãª'     => 'ê',
            'Ã®'     => 'î',
            'Ã´'     => 'ô',
            'Ã¢'     => 'â',
            'Ã‰'     => 'É',
            'Ã€'     => 'À',
            'Å“'     => 'œ',
            'â€™'    => "'"
        ];
        
        // Application du dictionnaire
        $contenu = str_replace(array_keys($dictionnaire), array_values($dictionnaire), $contenu);
        
        // Sauvegarde propre
        file_put_contents($fichier, $contenu);
        echo "✨ Soigné : <strong>" . basename($fichier) . "</strong><br>";
    }
}

echo "<h3>🎉 Les textes du panier sont réparés ! Passe à l'étape du logo ci-dessous.</h3>";