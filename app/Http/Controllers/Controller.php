<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API documentation for Byte5 Makeathon Backend',
    title: 'Byte5 Makeathon API',
    contact: new OA\Contact(email: 'laravel@byte5.com')
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'API Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    description: 'Enter your bearer token in the format: Bearer {token}',
    bearerFormat: 'Token',
    scheme: 'bearer'
)]
#[OA\Tag(
    name: 'Authentication',
    description: 'API endpoints for user authentication'
)]
abstract class Controller
{
    //
}
