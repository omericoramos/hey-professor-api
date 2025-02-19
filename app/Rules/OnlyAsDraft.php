<?php

namespace App\Rules;

use App\Models\Question;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OnlyAsDraft implements ValidationRule
{
    public function __construct(private readonly Question $question)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->question->status !== 'draft') {
            $fail('The question should be in draft status to be updated.');
        }
    }
}
