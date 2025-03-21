<?php

namespace App\Http\Requests\Question;

use App\Rules\WithQuestionMark;
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
            'question' => ['required', 'string', 'min:10', 'max:255', new WithQuestionMark(), 'unique:questions,question'],
        ];
    }

    public function messages(): array
    {
        return [
            'question.required'           => 'The question field is required.',
            'question.string'             => 'The question must be a string.',
            'question.max'                => 'The question must be at most 255 characters.',
            'question.min'                => 'The question must be at least 10 characters.',
            'question.with_question_mark' => 'The question must end with a question mark. (?)',
            'question.unique'             => 'The question already exists.',
        ];
    }
}
