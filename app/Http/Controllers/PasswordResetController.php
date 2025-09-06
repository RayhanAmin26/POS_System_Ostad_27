<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PasswordResetController extends Controller
{
    // send OTP to email
    public function sendOtp(Request $r){
        $r->validate(['email'=>'required|email']);
        $email = $r->email;
        $user = User::where('email', $email)->first();
        if(!$user) return response()->json(['error'=>'No user with that email'],404);

        $otp = mt_rand(100000,999999);
        DB::table('password_resets')->updateOrInsert(
            ['email'=>$email],
            ['otp'=>Hash::make($otp),'reset_token'=>null,'created_at'=>now()]
        );
        Mail::to($email)->send(new OTPMail($otp));
        return response()->json(['message'=>'OTP sent to email']);
    }

    // verify OTP and issue reset token (random string)
    public function verifyOtp(Request $r){
        $r->validate(['email'=>'required|email','otp'=>'required']);
        $record = DB::table('password_resets')->where('email',$r->email)->first();
        if(!$record) return response()->json(['error'=>'No OTP request found'],404);
        // check expiry (10 min)
        if($record->created_at && now()->diffInMinutes($record->created_at) > 10) return response()->json(['error'=>'OTP expired'],400);

        if(!\Illuminate\Support\Facades\Hash::check($r->otp, $record->otp)) return response()->json(['error'=>'Invalid OTP'],400);

        $token = Str::random(60);
        DB::table('password_resets')->where('email',$r->email)->update(['reset_token'=>$token]);
        return response()->json(['reset_token'=>$token]);
    }

    // reset password using reset_token
    public function resetPassword(Request $r){
        $r->validate(['email'=>'required|email','reset_token'=>'required','password'=>'required|min:6']);
        $record = DB::table('password_resets')->where('email',$r->email)->where('reset_token',$r->reset_token)->first();
        if(!$record) return response()->json(['error'=>'Invalid token'],400);
        $user = User::where('email',$r->email)->first();
        if(!$user) return response()->json(['error'=>'No user found'],404);
        $user->password = Hash::make($r->password);
        $user->save();
        // clear the reset row
        DB::table('password_resets')->where('email',$r->email)->delete();
        return response()->json(['message'=>'Password reset successful']);
    }
}
