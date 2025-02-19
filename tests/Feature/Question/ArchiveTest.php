<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, deleteJson};

it('archive a question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $question = Question::factory()->create([
        'question' => 'Lorem ipsum?',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);
    deleteJson(route('question.archive', $question))
        ->assertNoContent();

    assertDatabaseHas('questions', ['id' => $question->id]);
});
it('only the user who created the question can archive it', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();
    Sanctum::actingAs($user2, ['*']);

    $question = Question::factory()->create([
        'question' => '',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);

    deleteJson(route('question.archive', $question))
        ->assertForbidden();
    assertDatabaseHas('questions', ['id' => $question->id]);
});
