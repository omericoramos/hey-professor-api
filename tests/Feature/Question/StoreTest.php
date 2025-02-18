<?php

use App\Models\Question;
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
            'question' => 'Lorem?'
        ])
            ->assertJsonValidationErrors([
                'question' => 'The question must be at least 10 characters.'
            ]);
    });

    it('question should be unique', function () {

        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        Question::create([
            'question' => 'Lorem ipsum Jeremias?',
            'status'   => 'draft',
            'user_id'  => $user->id,
        ]);

        postJson(route('question.store'), [
            'question' => 'Lorem ipsum Jeremias?',
        ])->assertJsonValidationErrors([
            'question' => 'The question already exists.'
        ]);
    });

    it('after creting a new question we should return a status 201 with the following format', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $request = postJson(route('question.store'), [
            'question' => 'Lorem ipsum Jeremias?',
        ])->assertCreated();

        $question = Question::latest()->first();

        $request->assertJson([
            'data' => [
                'id' => $question->id,
                'question' => $question->question,
                'status' => $question->status,
                'created_at' => $question->created_at->format('Y-m-d'),
                'updated_at' => $question->updated_at->format('Y-m-d')
            ]

        ]);
    });
});
