<?php

// criar um usuário novo 
// verificar se o usuário realmente exite no banco
// verificar se a senha do usuário no banco bate com a senha passada na criação do usuário

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertTrue;

it('should be able to register a new user', function () {
    postJson(route('user.register'), [
        'name' => 'Omerico Araújo',
        'email' => 'omericoramos@gmail.com',
        'password' => 'password'
    ])->assertSessionHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'Omerico Araújo',
        'email' => 'omericoramos@gmail.com'
    ]);

    $user = User::whereEmail('omericoramos@gmail.com')->first();

    assertTrue(
        Hash::check('password', $user->password)
    );
});
