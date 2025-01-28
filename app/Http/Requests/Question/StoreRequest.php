<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'question' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'The question field is required.',
            'question.string'   => 'The question must be a string.',
            'question.max'      => 'The question must be at most 255 characters.',
        ];
    }
}
