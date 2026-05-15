<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateManagedUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $actor */
        $actor = $this->user();

        /** @var User $target */
        $target = $this->route('user');

        if (! $actor?->isSuperMasterAdmin() && ! $actor?->isVillageAdmin()) {
            return false;
        }

        if ($target->isSuperMasterAdmin() || $target->role === 'super_master_admin') {
            return false;
        }

        if ($actor->id === $target->id) {
            return false;
        }

        if ($actor->isVillageAdmin()) {
            return $target->village_id === $actor->village_id && $target->role === 'user';
        }

        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $target */
        $target = $this->route('user');
        $isSuperMaster = (bool) $this->user()?->isSuperMasterAdmin();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($target->id)],
            'password' => ['nullable', 'string', Password::defaults()],
            'role' => ['required', 'string', Rule::in($isSuperMaster ? ['user', 'village_admin'] : ['user'])],
            'village_id' => [$isSuperMaster ? 'required' : 'nullable', 'integer', 'exists:villages,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:professional_tax,personal_tax'],
        ];
    }
}
