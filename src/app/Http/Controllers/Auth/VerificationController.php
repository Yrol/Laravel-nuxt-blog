<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Symfony\Component\HttpFoundation\Response;

//use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be resent if the user did not receive the original email message.
    |
    */

    //use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify'); // signed emails with expiration
        $this->middleware('throttle:6,1')->only('verify', 'resend'); //how many times user requesting the email to be resent
    }

    public function verify(Request $request, User $user)
    {

        //check if the url is a valid signed url
        if(! URL::hasValidSignature()){
            return response()->json(['errors' => ['message' => 'Invalid verification link']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //check if user has already a verified account
        //Using the Laravel's MustVerifyEmail class extended in User class
        if($user->hasVerifiedEmail()){
            return response()->json(['errors' => ['message' => 'Email has already been verified']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

         //Using the Laravel's MustVerifyEmail class extended in User class
        $user->markEmailAsVerified();

        //Verify user
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified'], 200);
    }

    public function resend(Request $request)
    {

    }
}
