<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

#[OA\Info(title: 'Leptech LMS API', version: '1.0.0')]
#[OA\Server(url: '/api', description: 'API server')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Paste the token returned by /admin/login'
)]
abstract class Controller
{
}
