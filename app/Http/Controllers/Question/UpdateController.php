<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Http\Requests\Question\UpdateRequest;
use App\Models\Question;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $updateRequest, Question $question)
    {
        $question->question = $updateRequest->question;
        $question->save();

        return QuestionResource::make($question);
    }
}
