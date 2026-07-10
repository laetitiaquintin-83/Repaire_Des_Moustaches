    <footer>
        <?php $sitePrefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/public/') !== false ? '../' : ''; ?>
        <p>&copy; 2026 Le Repaire des Moustaches. Un tiers-lieu solidaire pour les chats et les humains. <a href="<?php echo $sitePrefix; ?>mentions-legales.php">Mentions légales</a> | <a href="<?php echo $sitePrefix; ?>cgv.php">CGV</a> | <a href="<?php echo $sitePrefix; ?>public/escape-game.php">Escape Game</a></p>
        <div class="reseaux-sociaux">
            <a href="#">Facebook</a> |
            <a href="#">Instagram</a> |
            <a href="<?php echo $sitePrefix; ?>login.php">Admin</a>
        </div>
    </footer>
</body>
</html>
