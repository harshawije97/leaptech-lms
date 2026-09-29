<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Organization
 */
class OrganizationIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'short_description' => $this->resource->shortDescription,
            'description' => $this->resource->description,
            'no_of_users' => $this->resource->noOfUsers,
            'no_of_admins' => $this->resource->noOfAdmins,
            'createdAt' => $this->resource->created_at,
        ];
    }
}
