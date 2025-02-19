<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user, ['*']);

    putJson(route('question.update', $question), [
        'question' => 'Actualizado?',
    ])->assertOk();

    assertDatabaseHas('questions', [
        'id'       => $question->id,
        'question' => 'Actualizado?',
        'user_id'  => $user->id,
    ]);
});
it('should be able to update a question and return a 200 status code with the updated data', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create([
        'user_id'  => $user->id,
        'question' => 'Pregunta original?',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = putJson(route('question.update', $question), [
        'question' => 'Actualizado?',
    ])->assertOk();

    $response->assertJson([
        'data' => [
            'id'         => $question->id,
            'question'   => 'Actualizado?',
            'status'     => $question->status,
            'created_by' => [
                'id'   => $user->id,
                'name' => $user->name,
            ],
            'created_at' => $question->created_at->format('Y-m-d H:i'),
            'updated_at' => $question->updated_at->format('Y-m-d H:i'),
        ],
    ]);
});

describe('validation rules', function () {

    it('question field is required', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        putJson(route('question.update', $question), [
            'question' => '',
        ])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    it('question ending with a question mark', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);
        Sanctum::actingAs($user, ['*']);

        putJson(route('question.update', $question), [
            'question' => 'Lorem ipsum Omerico',
        ])->assertJsonValidationErrors([
            'question' => 'The question must end with a question mark. (?)',
        ]);
    });

    it('question must contain at least 10 characters', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);
        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);
        putJson(route('question.update', $question), [
            'question' => 'Lorem?',
        ])
            ->assertJsonValidationErrors([
                'question' => 'The question must be at least 10 characters.',
            ]);
    });

    it('question should be unique', function () {

        $user = User::factory()->create();

        Question::factory()->create([
            'user_id'  => $user->id,
            'question' => 'Lorem ipsum Jeremias?',
            'status'   => 'draft',
        ]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        putJson(route('question.update', $question), [
            'question' => 'Lorem ipsum Jeremias?',
        ])->assertJsonValidationErrors([
            'question' => 'The question already exists.',
        ]);
    });

    it('question should be unique only if id is different', function () {

        $user = User::factory()->create();

        $question = Question::factory()->create([
            'user_id'  => $user->id,
            'question' => 'Lorem ipsum Jeremias?',
            'status'   => 'draft',
        ]);

        Sanctum::actingAs($user, ['*']);

        putJson(route('question.update', $question), [
            'question' => 'Lorem ipsum Jeremias?',
        ])->assertOk();
    });

    test('should be able to edit only if the status question is in draft', function () {
        $user = User::factory()->create();

        $question = Question::factory()->create([
            'user_id'  => $user->id,
            'question' => 'Lorem ipsum Jeremias?',
            'status'   => 'published',
        ]);

        Sanctum::actingAs($user, ['*']);

        putJson(route('question.update', $question), [
            'question' => 'Lorem ipsum Jeremias?',
        ])->assertJsonValidationErrors([
            'question' => 'The question should be in draft status to be updated.',
        ]);
    });
});

describe('security', function () {

    // garantindo que somente o usuário que criou a pergunta possa atualizar-la
    test('only the person who created the question can update it', function () {
        $user  = User::factory()->create();
        $user2 = User::factory()->create();

        $question = Question::factory()->create([
            'question' => 'Pregunta original?',
            'status'   => 'draft',
            'user_id'  => $user->id,
        ]);

        // Logo com o segundo usuário
        Sanctum::actingAs($user2, ['*']);

        putJson(route('question.update', $question), [
            'question' => 'Actualizado a pergunta?',
        ])->assertForbidden(); // deve negar a atualização da pergunta

        // garante que nada foi atualizado na pergunta
        assertDatabaseHas('questions', [
            'question' => 'Pregunta original?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);
    });
});
