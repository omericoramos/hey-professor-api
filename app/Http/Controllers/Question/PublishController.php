<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\json;

class PublishController extends Controller
{
    use AuthorizesRequests;
    public function __invoke(Question $question)
    {
        // aborte a ação se a pergunta nao estiver em "draft", e retorne 403
        abort_unless($question->status === 'draft', Response::HTTP_FORBIDDEN);
        
        $this->authorize('update', $question);

        $question->update([
            'status' => 'published',
        ]);

        return Response::HTTP_OK;
    }
}
