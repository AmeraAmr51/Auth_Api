<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class VerificationApiController extends Controller
{
    //
    // use VerifiesEmails;

    /* Mark the authenticated user’s email address as verified.

    *

    * @param \Illuminate\Http\Request $request

    * @return \Illuminate\Http\Response

    */

    public function verify(Request $request) {

        $userID = $request['id'];

        $user = User::findOrFail($userID);

        $date = date("Y-m-d H:i:s");

        $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature

        $user->save();
        dd($user);

        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONT_URL') . '/email/verify/already-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(env('FRONT_URL') . '/email/verify/success');
    
    

    }

    /**

    * Resend the email verification notification.

    *

    * @param \Illuminate\Http\Request $request

    * @return \Illuminate\Http\Response

    */

    public function resend(Request $request)

    {

    $request->user()->sendEmailVerificationNotification();

    return response()->json('The notification has been resubmitted');

    }

}
