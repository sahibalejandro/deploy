<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatabase extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'regex:/^([a-z0-9_]){8,20}$/i'],
            'user' => ['required', 'regex:/^([a-z0-9_]){8,16}$/i'],
            'password' => ['required'],
        ];
    }
}
