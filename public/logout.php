<?php
declare(strict_types=1);

$sitePrefix = '';
session_start();
session_destroy();
header('Location: login.php');
exit;
