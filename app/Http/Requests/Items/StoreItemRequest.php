<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string|max:100|nullable',
            'description' => 'string|max:200|nullable',
            'colours' => 'array|required',
            'colours.*' => 'size:7|starts_with:#',
        ];
    }
}
