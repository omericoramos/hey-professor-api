<?php

use App\Models\User;
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
