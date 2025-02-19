<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\DeleteRequest;
use App\Models\Question;

class DeleteController extends Controller
{
    public function __invoke(DeleteRequest $request, Question $question)
    {
        $question->question = $request->question;
        $question->forceDelete();

        return response()->noContent();
    }
}
