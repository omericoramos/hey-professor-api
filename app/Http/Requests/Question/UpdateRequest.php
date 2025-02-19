<?php

namespace App\Http\Requests\Question;

use App\Rules\WithQuestionMark;
use App\Rules\OnlyAsDraft;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $question = $this->route()->question; // @phpstan-ignore-line

        return Gate::allows('update', $question);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $question = $this->route()->question; // @phpstan-ignore-line
        return [
            'question' => [
                'required',
                'string',
                'min:10',
                'max:255',
                new WithQuestionMark(),
                new OnlyAsDraft($question),
                Rule::unique('questions')->ignoreModel($question),
            ],
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
