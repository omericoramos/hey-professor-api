<?php

use App\Models\User;
use App\Rules\WithQuestionMark;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to store a question', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    postJson(route('question.store'), [
        'question' => 'Lorem ipsum Jeremias?',
    ])->assertSuccessful();

    assertDatabaseHas('questions', [
        'question' => 'Lorem ipsum Jeremias?',
        'user_id'  => $user->id,
    ]);
});

test('after storing a new question, i need to make sure that it creates on _draft_ status', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    postJson(route('question.store'), [
        'question' => 'Lorem ipsum Jeremias?',
    ])->assertSuccessful();

    assertDatabaseHas('questions', [
        'question' => 'Lorem ipsum Jeremias?',
        'status'   => 'draft',
        'user_id'  => $user->id,
    ]);
});

describe('validation rules', function () {

    it('question field is required', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);


        postJson(route('question.store'), [])
            ->assertJsonValidationErrors([
                'question' => 'required'
            ]);
    });

    it('question ending with a question mark', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        postJson(route('question.store'), [
            'question' => 'Lorem ipsum Omerico',
        ])->assertJsonValidationErrors([
            'question' => 'The question must end with a question mark. (?)'
        ]);
    });

    it('question must contain at least 10 characters', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        postJson(route('question.store'), [
            'question' => 'Lorem'
        ])
            ->assertJsonValidationErrors([
                'question' => 'The question must be at least 10 characters.'
            ]);
    });
});
