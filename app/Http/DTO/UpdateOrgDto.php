<?php

namespace App\Http\DTO;

final readonly class UpdateOrgDto
{
    public function __construct(
        public ?string $name = null,
        public ?string $shortDescription = null,
        public ?string $description = null,
        public ?int $noOfUsers = null,
        public ?int $noOfAdmins = null,
    ) {
    }

    public function toModelAttributes(): array
    {
        return array_filter([
            'name' => $this->name,
            'short_description' => $this->shortDescription,
            'description' => $this->description,
            'no_of_users' => $this->noOfUsers,
            'no_of_admins' => $this->noOfAdmins,
        ], fn($value) => $value !== null);
    }
}
