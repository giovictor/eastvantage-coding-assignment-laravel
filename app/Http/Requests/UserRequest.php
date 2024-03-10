<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Role;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleIds = Role::get()->pluck('id')->toArray();

        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                !empty($this->route()->user->id) ? Rule::unique('users')->ignore($this->route()->user->id) : Rule::unique('users')
            ],
            'roles' => 'required|array',
            'roles.*' => Rule::in($roleIds)
        ];
    }

    public function messages(): array
    {
        return [
            'roles.*.in' => 'Invalid role for item #:position.'
        ];
    }
}
