<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $class = get_class($user);
        $keyName = $user->getKeyName();

        $uniqueRule = $user instanceof \App\Models\Admin 
            ? Rule::unique($class, 'username')->ignore($user->getKey(), $keyName)
            : Rule::unique($class, 'email')->ignore($user->getKey(), $keyName);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                $uniqueRule,
            ],
        ];
    }
}
