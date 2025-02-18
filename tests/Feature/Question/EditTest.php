<?php

use App\Models\Question;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
 use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {
    $user = User::factory()->create();
    $question = Question::factory()->create([
        'user_id' => $user->id
    ]);

    Sanctum::actingAs($user, ['*']);

    putJson(route('question.update', $question), [
        'question' => 'Actualizado?',
    ])->assertOk();

    assertDatabaseHas('questions', [
        'id' => $question->id,
        'question' => 'Actualizado?',
        'user_id' => $user->id,
    ]);
});