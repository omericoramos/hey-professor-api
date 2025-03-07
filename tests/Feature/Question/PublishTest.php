<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertNotSoftDeleted, assertSoftDeleted, deleteJson, putJson};

it('publish a question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $question = Question::factory()->create([
        'question' => '',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);

    putJson(route('question.publish', $question))
        ->assertSuccessful();
});

it('only a question draft can be published', function () {
    $user  = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $question = Question::factory()->create([
        'question' => '',
        'status'   => 'published',
        'user_id'  => $user->id,
    ]);

    putJson(route('question.publish', $question))
        ->assertForbidden();
});

it('only the user who created the question can  publish it', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();
    Sanctum::actingAs($user2, ['*']);

    $question = Question::factory()->create([
        'question' => 'publicando a pergunta?',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);
    putJson(route('question.publish', $question))
        ->assertForbidden();
});
