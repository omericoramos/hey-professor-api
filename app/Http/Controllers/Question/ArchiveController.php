<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArchiveController extends Controller
{
    use AuthorizesRequests;
    public function __invoke(Question $question)
    {
        $this->authorize('archive', $question);
        $question->delete();

        return response()->noContent();
    }
}
