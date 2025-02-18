<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $question = user()->questions()->find($request->id);
        $question->question = $request->question;
        $question->save();
        return QuestionResource::make($question);
    }
}
