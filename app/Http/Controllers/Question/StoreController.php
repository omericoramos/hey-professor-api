<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        Question::create([
            'question' => $request->question,
            'status'   => 'draft',
            'user_id'  => Auth::user()->id,
        ]);
    }
}
