<?php

namespace App\Http\Requests;

use App\Http\DTO\UpdateOrgDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organization = $this->route('org');
        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('organization', 'name')->ignore($organization?->id)],
            'shortDescription' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'noOfUsers' => ['sometimes', 'integer', 'min:0'],
            'noOfAdmins' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    /**
     * exceptional validations
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fillable = ['name', 'shortDescription', 'description', 'noOfUsers', 'noOfAdmins'];

            $hasAnyField = collect($fillable)->contains(fn($field) => $this->has($field));

            if (!$hasAnyField) {
                $validator->errors()->add('body', 'At least one field must be provided to update');
            }
        });
    }

    /**
     * Construct the DTO and apply
     */
    public function toDto(): UpdateOrgDto
    {
        $data = $this->validated();

        return new UpdateOrgDto(
            name: $data['name'] ?? null,
            shortDescription: $data['shortDescription'] ?? null,
            description: array_key_exists('description', $data) ? $data['description'] : null,
            noOfUsers: $data['noOfUsers'] ?? null,
            noOfAdmins: $data['noOfAdmins'] ?? null,
        );
    }
}
