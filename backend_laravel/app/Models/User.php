<?php

namespace App\Models;

 use App\Mail\ForgotPassword;
 use App\Mail\Verification;
 use Carbon\Carbon;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Mail;
 use Illuminate\Support\Str;
 use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Contracts\Auth\CanResetPassword;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function sendEmailVerification(){
        $token = Str::random(64);
        $this->hash = $token;
        $this->save();
        $fronted_url = env('FRONTEND_URL');
        $url_user =  $fronted_url . '/verify/' . $this->id.'/'. $this->hash;
        $correo = new Verification($url_user);
        Mail::to($this->email)->send($correo);

    }
    public function sendEmailForgotPassword(){
        $search_reset = DB::table('password_reset_tokens')->where('email',$this->email)->first();
        $fronted_url = env('FRONTEND_URL');
        if(!$search_reset) {
            $token = Str::random(64);
            DB::table('password_reset_tokens')->insert([
                'email' => $this->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $url_user = $fronted_url . '/reset-password/' . $token;
            $correo = new ForgotPassword($url_user);
            Mail::to($this->email)->send($correo);
        }
        else{
            $token = Str::random(64);
            $search_reset->token = $token;
            $search_reset->save();
            $url_user = $fronted_url . '/reset-password/' . $token;
            $correo = new ForgotPassword($url_user);
            Mail::to($this->email)->send($correo);
        }
    }
}
