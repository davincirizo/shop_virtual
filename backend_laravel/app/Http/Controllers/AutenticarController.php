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
use Illuminate\Auth\Events\Registered;

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
//        $user = User::create($request->all());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        $user->sendEmailVerificationNotification();

//        event(new Registered($user));

        return response()->json([
            'res' => true,
            'msg' => 'Se ha enviado Mensaje a su corre Revise para confirmar'
        ],200);

    }


    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::all();
        $user ->where('id', '=',$id);
//            ->where('verification_token', $hash)
//            ->firstOrFail();

        $user[0]->email_verified_at = now();
//        $user[0]->verification_token = null;
        $user[0]->update();

        return response()->json([
            'message' => 'Correo electrónico verificado con éxito.'
        ], 200);
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
