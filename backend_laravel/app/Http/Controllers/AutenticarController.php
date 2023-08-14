<?php

namespace App\Http\Controllers;

use App\Mail\Verification;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use App\Http\Requests\RegistroRequest;
use App\Http\Requests\AccesoRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;
use \Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class AutenticarController  extends Controller

{

    public function registro(Request $request){
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
        $user->sendEmailVerification();


        return response()->json([
            'res' => true,
            'msg' => 'Se ha enviado Mensaje a su corre Revise para confirmar'
        ],200);


    }

    public function resend_email(Request $request ){
//        if() {
        $user_all = User::all();
        $user = $user_all->where('email', '=',$request->email)->first();
        if($user) {
            if( !$user->email_verified_at) {
                $user->sendEmailVerification();
                return response()->json([
                    'message' => 'Mensaje enviado correctamente. Revise su correo nuevamente'
                ], 201);
            }
            else{
                return response()->json([
                    'message' => 'Este usuario tiene el correo confirmado'
                ], 202);
            }
        }
        else{
            return response()->json([
                'message' => 'Este usuario no se encuantra'
                ], 404);
        }
//        }
    }


    public function verifyEmail(Request $request, $id, $hash)
    {
        $user_all = User::all();

        $user = $user_all->where('id', '=',$id)->first();
        if(!$user){
            return response()->json([
                'message' => 'Este usuario no existe'
            ], 400);
        }

        if ($hash != $user->hash) {
            return response()->json([
                'message' => 'URL Erronea'
            ], 400);
        }

        if (!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
        }
        else{
            return response()->json([
                'message' => 'Este correo Esta actualmente Verificado'
            ], 201);
        }

        return response()->json([
            'message' => 'Correo electrónico verificado con éxito.'
        ], 200);
    }

    public function forgot_password(Request $request){
        $request->validate(['email' => 'required|email']);
        $user_all = User::all();
        $user = $user_all->where('email', '=', $request->email)->first();
        if(!$user){
            return response()->json([
                'status' => false,
                'errors' => 'Usuario no Encontrado',
            ],404);
        }

        $user->sendEmailForgotPassword();

        return response()->json([
            'status' => true,
            'errors' => 'Le enviamos el correo para que autentique su contrasenna',
        ],200);

    }

    public function get_forgot_password(Request $request,$token){
        $search_reset = DB::table('password_reset_tokens')->where('token',$token)->first();
        if(!$search_reset){
            return response()->json([
                'status' => false,
                'message' => 'Token No Valiudo',
            ],404);
        }
        else{
            $user_all = User::all();
            $user = $user_all->where('email','=',$search_reset->email)->first();
            $user->password = $request->password;
            $user->save();
            $search_delete = DB::table('password_reset_tokens')->where('token',$token)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Contrasenna correactamente modificada',
            ],200);

        }


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
