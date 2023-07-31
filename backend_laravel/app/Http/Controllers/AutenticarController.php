<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use App\Http\Requests\RegistroRequest;
use App\Http\Requests\AccesoRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


class AutenticarController extends Controller
{
    public function registro(Request $request ){
       $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
           'password' => 'required',
       ];
        $validator = \Validator::make($request->input(),$rules);
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ],400);
        }
        $user = new User();
        if($request->file('image')){
            $file = $request->file('image');
            $url = Storage::put('users',$file);
            $user->image = $url;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado correctamente'
        ],200);

    }

    public function login(Request $request ){
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
        $validator = \Validator::make($request->input(),$rules);
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ],400);
        }
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'status' => false,
                'errors' => 'Usuario o contrasenna incorrecta',
            ],401);
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'errors' => 'Usuario o contrasenna incorrecta',
            ],401);
        }

       $token = $user->createToken($request->email)->plainTextToken;
       return response()->json([
            'res' => true,
            'token' => $token,
           'data' => $user
       ],200);

    }

    public function logout(Request $request){


//        $user = $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Usuario deslogueado correctamente',
       ],200);

    }




}
