<?php

declare(strict_types=1);

function getStripeSecretKey(): string
{
    $key = getenv('STRIPE_SECRET_KEY');

    if (!is_string($key) || $key === '') {
        throw new RuntimeException('La cle secrete Stripe n\'est pas configuree.');
    }

    return $key;
}