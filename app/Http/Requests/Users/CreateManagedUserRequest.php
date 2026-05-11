<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateManagedUserRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isSuperMaster = (bool) $this->user()?->isSuperMasterAdmin();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            'role' => ['required', 'string', 'in:user,village_admin'],
            'village_id' => [$isSuperMaster ? 'required' : 'nullable', 'integer', 'exists:villages,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:professional_tax,personal_tax'],
        ];
    }
}
