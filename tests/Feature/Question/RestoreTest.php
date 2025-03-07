<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertNotSoftDeleted, assertSoftDeleted, deleteJson, putJson};

it('restore a question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $question = Question::factory()->create([
        'question' => '',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);

    $question->delete();

    assertSoftDeleted('questions', ['id' => $question->id]);

    putJson(route('question.restore', $question))
        ->assertNoContent();

    assertNotSoftDeleted('questions', ['id' => $question->id]);
});
it('only a question deleted can be restored', function () {
    $user  = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $question = Question::factory()->create([
        'question' => '',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);

    putJson(route('question.restore', $question))
        ->assertNotFound();

    assertNotSoftDeleted('questions', ['id' => $question->id]);
});

it('only the user who created the question can  restore it', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();
    Sanctum::actingAs($user2, ['*']);

    $question = Question::factory()->create([
        'question' => '',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);

    $question->delete();

    putJson(route('question.restore', $question))
        ->assertForbidden();
    assertSoftDeleted('questions', ['id' => $question->id]);
});
