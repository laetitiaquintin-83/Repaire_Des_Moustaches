<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Desactiver en production
ini_set('log_errors', '1');

// Création du dossier de logs
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}
ini_set('error_log', $logDir . '/php-errors.log');

// Gestionnaire d'erreurs
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("[$errno] $errstr in $errfile:$errline");
    
    if ($errno === E_USER_ERROR || $errno === E_PARSE) {
        http_response_code(500);
        die('Une erreur technique est survenue. Notre équipe a été notifiée.');
    }
});

function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // Détection de l'environnement (Local vs Plesk)
    $isLocal = ($_SERVER['HTTP_HOST'] ?? '') === 'localhost' || stripos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false;

    // Variables d'environnement : à définir dans Plesk / serveur de production.
    $host = getenv('DB_HOST') ?: ($isLocal ? '127.0.0.1' : 'localhost');
    $dbname = getenv('DB_NAME') ?: ($isLocal ? 'repaire_des_moustaches' : 'laetitia-quintin_repaire_des_moustaches');
    $username = getenv('DB_USER') ?: ($isLocal ? 'root' : 'laetitia-quintin');
    $password = getenv('DB_PASS') ?: '';

    $charset = 'utf8mb4';

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset);

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Erreur de connexion BDD : " . $e->getMessage());
        http_response_code(500);
        die("Impossible de se connecter à la base de données.");
    }
}

/** Génère un token CSRF. */
function generateCSRFToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Valide un token CSRF. */
function validateCSRFToken(string $token): bool
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}