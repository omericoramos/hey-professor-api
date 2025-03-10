<?php

// criar um usuário novo 
// verificar se o usuário realmente exite no banco
// verificar se a senha do usuário no banco bate com a senha passada na criação do usuário

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertTrue;

// it('should be able to register a new user', function () {
//     postJson(route('user.register'), [
//         'name' => 'Omerico Araújo',
//         'email' => 'omericoramos@gmail.com',
//         'password' => 'password'
//     ])->assertSessionHasNoErrors();

//     assertDatabaseHas('users', [
//         'name' => 'Om',
//         'email' => 'omericoramos@gmail.com'
//     ]);

//     $user = User::whereEmail('omericoramos@gmail.com')->first();

//     assertTrue(
//         Hash::check('password', $user->password)
//     );
// });

describe('validations', function () {

    test('validate name', function ($rule, $value, $meta = []) {
        postJson(route('user.register'), ['name' => $value])
            ->assertJsonValidationErrors([
                'name' => [__('validation.' . $rule, array_merge(['attribute' => 'name'], $meta))]
            ]);
    })->with([
        'required' => ['required', ''],
        'min:3' => ['min', 'AB', ['min' => 3]],
        'max:60' => ['max', str_repeat('A', 61), ['max' => 60]]
    ]);
});
