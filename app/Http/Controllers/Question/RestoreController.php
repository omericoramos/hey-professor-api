<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestoreController extends Controller
{
    use AuthorizesRequests;
    public function __invoke(int $id)
    {
        $question = Question::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $question);
        $question->restore();
        return response()->noContent();
    }
}
