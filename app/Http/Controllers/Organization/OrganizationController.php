<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationIndexResource;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrganizationController extends Controller
{
    #[OA\Post(
        path: '/admin/org/create',
        tags: ['Organization'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'shortDescription'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Acme Inc.'),
                    new OA\Property(property: 'shortDescription', type: 'string', example: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas venenatis enim at dolor rutrum, non volutpat metus faucibus. Aliquam tincidunt lorem non nisi vestibulum fermentum.'),
                    new OA\Property(property: 'description', type: 'string', example: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean orci velit, varius vitae massa pellentesque, dapibus rhoncus dui. Proin at fringilla ipsum, non venenatis erat. Mauris cursus elit vel dictum condimentum. Mauris rhoncus auctor convallis. Mauris nisl tellus, aliquam lobortis interdum sed, consectetur vitae nulla. Integer laoreet metus nec neque scelerisque, sed lacinia diam interdum. Sed vitae ante nulla. Quisque eu felis nec orci molestie blandit. Pellentesque ac enim vestibulum, molestie nisi eget, interdum metus. Suspendisse sagittis auctor nibh vel sollicitudin. Sed dignissim urna eu est egestas iaculis. Nulla nec porta massa. Duis nunc elit, ornare sit amet justo quis.'),
                    new OA\Property(property: 'noOfUsers', type: 'integer', default: 0, example: 10),
                    new OA\Property(property: 'noOfAdmins', type: 'integer', default: 0, example: 2),
                ],
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Organization created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Not allowed'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 409, description: 'Organization already exists')
        ]
    )]
    public function createOrganization(Request $request): JsonResponse
    {
        // check validations
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shortDescription' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'noOfUsers' => ['sometimes', 'integer', 'min:0'],
            'noOfAdmins' => ['sometimes', 'integer', 'min:0'],
        ]);
        // check if the name exists
        if (Organization::where('name', $data['name'])->first()) {
            // throw an error
            throw new HttpException(409, 'Organization already exists');

        }

        $organization = Organization::create([
            'name' => $data['name'],
            'short_description' => $data['shortDescription'],
            'description' => $data['description'] ?? null,
            'no_of_users' => $data['noOfUsers'] ?? 0,
            'no_of_admins' => $data['noOfAdmins'] ?? 0,
        ]);
        // return information
        return (new OrganizationResource($organization))
            ->response()
            ->setStatusCode(201);
    }

    // Get all organizations
    #[OA\Get(
        path: '/admin/org/all',
        tags: ['Organization'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'perPage', in: 'query', schema: new OA\Schema(type: 'integer', maximum: 100)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of organizations'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Not allowed'),
        ]
    )]
    public function getAllOrganizations(Request $request): JsonResponse
    {
        // create query
        $query = Organization::query();
        // get by search keyword
        if ($request->filled('search')) {
            $search = strtolower($request->string('search'));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        }
        // pagination (get all)
        $page = min($request->integer('perPage', 15), 100);
        $organizations = $query->paginate($page);
        // return the json
        return (OrganizationIndexResource::collection($organizations)
            ->response()
            ->setStatusCode(200));
    }
}

