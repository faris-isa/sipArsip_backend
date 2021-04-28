<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'stripe/*',
        // 'http://127.0.0.1/backend/api/products/status/*',
        // 'http://127.0.0.1/backend/api/offers/status/*',
        // 'https://127.0.0.1/backend/api/products/status/*',
        // 'https://127.0.0.1/backend/api/offers/status/*',
    ];
}
