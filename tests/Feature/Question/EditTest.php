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
it('should be able to update a question and return a 200 status code with the updated data', function () {
    $user = User::factory()->create();
    $question = Question::factory()->create([
        'user_id' => $user->id,
        'question' => 'Pregunta original?'
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = putJson(route('question.update', $question), [
        'question' => 'Actualizado?',
    ])->assertOk();

    $response->assertJson([
        'data' => [
            'id' => $question->id,
            'question' => 'Actualizado?',
            'status' => $question->status,
            'created_by' => [
                'id' => $user->id,
                'name' => $user->name
            ],
            'created_at' => $question->created_at->format('Y-m-d H:i'),
            'updated_at' => $question->updated_at->format('Y-m-d H:i')
        ]
    ]);
});