<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): array
    {
        /** @var User $user */
       $user = User::create($request->only(['name', 'email', 'password']));
       $token = $user->createToken($user->name)->plainTextToken;
       return [
           'user' =>$user,
           'token' => $token
       ];
    }

    public function login(Request $request){
      $data = $request->validate([
         'email' => 'required | email | exists:users',
         'password' => 'required | string'
      ]);
      $user = User::where(['email' => $data['email']])->first();
      if(!$user || !Hash::check($data['password'], $user->password)){
          return \response("Wrong credentials", Response::HTTP_UNAUTHORIZED);
      }
      $abilities =
        $token = $user->createToken($user->name)->plainTextToken;

        return [
            'user' =>$user,
            'token' => $token
        ];
    }

    public function logout(Request $request){
      $request->user()->tokens()->delete();
      return \response('ok');
    }
}
