<?php

declare(strict_types=1);

// Charge automatiquement le fichier .env à la racine si la variable n'existe pas encore
if (getenv('STRIPE_SECRET_KEY') === false) {
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

function getStripeSecretKey(): string
{
    $key = getenv('STRIPE_SECRET_KEY');

    if (!is_string($key) || $key === '') {
        throw new RuntimeException('La cle secrete Stripe n\'est pas configuree.');
    }

    return $key;
}