<?php
// ============================================================
// DÉTECTION AUTOMATIQUE DU CHEMIN (peu importe où on est)
// ============================================================
// Si $sitePrefix n'est pas défini, on le calcule
if (!isset($sitePrefix)) {
    // Est-ce qu'on est dans un sous-dossier (ex: /public/) ?
    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
    $sitePrefix = (strpos($scriptPath, '/public/') !== false) ? '../' : '';
}
// ============================================================
?>
<footer>
    <p>
        &copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains.
        <a href="<?php echo $sitePrefix; ?>mentions-legales.php">Mentions légales</a> |
        <a href="<?php echo $sitePrefix; ?>cgv.php">CGV</a> |
        <a href="<?php echo $sitePrefix; ?>public/escape-game.php">Escape Game</a>
    </p>
    <div class="reseaux-sociaux">
    <a href="<?php echo $sitePrefix; ?>facebook-preview.php">Facebook</a> |
<a href="<?php echo $sitePrefix; ?>instagram-preview.php">Instagram</a> |
    <a href="<?php echo $sitePrefix; ?>login.php">Admin</a>
</div>
</footer>
</body>
</html>
