<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Repositories\User\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;

class passportAuthController extends Controller
{
    //
    /**
     * @var ApiResponse
     * @var UserRepositoryInterface
     */
    private $response;


    public function __construct(ApiResponse $response, UserRepositoryInterface $repository)
    {

        $this->response = $response;
        $this->repository = $repository;
    }

    public function sendOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        // User already verified
        if ($request->user()->email_verified_at)
            return $this->response->status(false, 400)->massage(__("Email already Verified"))->returnJson();

        // throttle
        // $otp_expires_time = Carbon::now()->addSeconds(20);
        // Cache::put(['otp_expires_time'], $otp_expires_time);
        // Log::info("otp = ".$otp);



        // generate new otp
        $otp = random_int(100000, 999999);
        // update otp
        $user = auth()->user();
        $user->otp = $otp;
        $user->otp_expired = now();
        // set the otp expiration date
        $user->save();
        $user->sendOTPNotification($otp);
        return $this->response->status(true, 200)->massage(__("OTP Sent"))->returnJson();
    }



    public function register(UserRequest $request)
    {

        $user = $this->repository->create($request->validated());
        // generate new otp
        $otp = random_int(10000, 99999);
        $user->otp = $otp;
        $user->otp_expired = now();

        
        

        return $this->response->status(true, 200)->massage(__("Sucsess Registrasion"))->returnJson();
    }

    public function verityOTP(Request $request)
    {
       
        $user_otp=$request['otp'];


        // 1. make sure the user is not verified
        // if ($request->user()->email_verified_at)
        //     return $this->response->status(false, 400)->massage(__("Email already Verified"))->returnJso $currentDateTimn();

        

        // 2. check if the otp is expired
        // $currentDateTime= $user->otp_expired;
        $newDateTime = Carbon::now()->addMinutes(5);

        // if ($currentDateTime < $newDateTime) 
        // {
            // 3. make sure that the otp in the db is equal to the one in the request
            // $otpCode = $user->otp;
                       
            if ('958582' == $user_otp) 
            {
                dd('1');
                // $user->email_verified_at = Carbon::now();
                return $this->response->status(true, 200)->massage(__("Accepted OTP"))->returnJson();
            }
            return $this->response->status(false, 400)->massage(__("Wrong OTP"))->returnJson();
        }
    //     return $this->response->status(false, 400)->massage(__("Expired OTP"))->returnJson();
    // }







    // public function login(Request $request)
    // {
    //     $request->request->add([
    //         'client_id' => env("PASSPORT_CLIENT_ID"),
    //         'client_secret' => env("PASSPORT_CLIENT_SECRET"),
    //         'grant_type' => 'password',
    //         'scope' => '',
    //     ]);
    //     $tokenRequest = $request->create('/oauth/token', 'POST', $request->all());
    //     $response = Route::dispatch($tokenRequest);

    //     $user = User::where('email', $request->username)->first();

    //     if ($user->email_verified_at !== NULL) {
    //         $success['message'] = "Login successfull";
    //         return $response;
    //     } else {
    //         return response()->json(['error' => 'Please Verify Email'], 401);
    //     }
    // }

    // /**
    //  * This method returns authenticated user details
    //  */
    // public function authenticatedUserDetails()
    // {
    //     //returns details
    //     return response()->json(['authenticated-user' => auth()->user()], 200);
    // }


}
