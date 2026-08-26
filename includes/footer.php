<?php
// Détection du préfixe
if (!isset($sitePrefix)) {
    $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
    $sitePrefix = (strpos($scriptPath, '/public/') !== false) ? '../' : '';
}
// Module cookies
if (file_exists(__DIR__ . '/cookies-modal.php')) {
    include __DIR__ . '/cookies-modal.php';
}
?>

<footer>
    <div class="footer-container">
        <!-- Liens légaux -->
        <p>
            &copy; 2026 Le Repaire des Moustaches. 
            <a href="<?php echo $sitePrefix; ?>mentions-legales.php">Mentions légales</a> |
            <a href="<?php echo $sitePrefix; ?>cgv.php">CGV</a> |
            <a href="<?php echo $sitePrefix; ?>cgu.php">CGU</a> |
            <a href="<?php echo $sitePrefix; ?>confidentialite.php">Confidentialité</a>
        </p>

        <!-- Liens externes et admin -->
        <div class="reseaux-sociaux">
            <a href="<?php echo $sitePrefix; ?>facebook-preview.php" target="_blank" rel="noopener">Facebook</a> |
            <a href="<?php echo $sitePrefix; ?>instagram-preview.php" target="_blank" rel="noopener">Instagram</a> |
            <a href="<?php echo $sitePrefix; ?>login.php">🔐 Admin</a>
        </div>
    </div>
</footer>

</body>
</html>