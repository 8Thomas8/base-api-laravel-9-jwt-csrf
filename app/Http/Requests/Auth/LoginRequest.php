<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $credentials = $this->only(
            'email',
            'password'
        );
        return auth()
            ->claims(['csrf-token' => Str::random(32)])
            ->attempt($credentials);
    }

    /**
     * Define validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
