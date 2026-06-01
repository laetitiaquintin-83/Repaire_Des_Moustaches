<?php

declare(strict_types=1);

// Configuration de la gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', '0');  // Ne pas afficher les erreurs en production
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

// Gestionnaire d'erreurs personnalisé
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("[$errno] $errstr in $errfile:$errline");
    
    // Ne pas afficher les erreurs à l'utilisateur
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

    $host = '127.0.0.1';
    $dbname = 'repaire_des_moustaches';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset);

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    return $pdo;
}

/**
 * Génère un token CSRF et le stocke en session
 */
function generateCSRFToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valide un token CSRF depuis le formulaire
 */
function validateCSRFToken(string $token): bool
{
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}
