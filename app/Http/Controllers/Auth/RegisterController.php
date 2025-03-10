<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = request()->validate([
            'name' => ['required', 'min:3', 'max:60']
        ]);
        dd($data);
        // $data['password'] = password_hash($request->password,null);
        // User::insert($data);
    }
}
