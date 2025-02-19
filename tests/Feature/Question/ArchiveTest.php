<?php


use App\Models\Question;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;

it('archive a question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $question = Question::factory()->create([
        'question' => 'Lorem ipsum?',
        'status' => 'draft',
        'user_id' => $user->id,
    ]);
    deleteJson(route('question.archive', $question))
        ->assertNoContent();

    assertDatabaseHas('questions', ['id' => $question->id]);
});
