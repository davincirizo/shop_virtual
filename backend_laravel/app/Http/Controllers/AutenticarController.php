<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistroRequest;
use App\Http\Requests\AccesoRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutenticarController extends Controller
{
    public function registro(RegistroRequest $request ){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado correctamente'
        ],200);

    }

    public function login(AccesoRequest $request ){
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msg' => ['The provided credentials are incorrect.'],
            ]);
        }

       $token = $user->createToken($request->email)->plainTextToken;
       return response()->json([
            'res' => true,
            'token' => $token
       ],200);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        // $user->tokens()->where('id', $tokenId)->delete();
        // $user->tokens()->delete();
        // $request->user()->currentAccesToken()->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Usuario deslogueado correctamente'
       ],200);

    }




}
