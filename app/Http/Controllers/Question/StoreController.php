<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
       $question = Question::create([
            'question' => $request->question,
            'status'   => 'draft',
            'user_id'  => Auth::user()->id,
        ]);

        return QuestionResource::make($question);
    }
}
