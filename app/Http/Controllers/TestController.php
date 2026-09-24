<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

class TestController extends Controller
{
    #[OA\Get(
        path: '/test',
        tags: ['Test'],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
        ])]

    public function index(): string
    {
        return response()->json(['message' => 'Test controller is working']);
    }
}
