<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;

use App\Http\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class AuthApiController extends Controller
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
    public function register(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            // Check if user existed OR not 
            $check = User::where('email', $request['email'])->first();

            if ($check)
                return $this->response->status(false, 400)->massage(__("Your Mail is Already Existed"))->returnJson();

            $user = $this->repository->create($request->validated());

            // Generate new otp and Update it in DB
            $otp = random_int(10000, 99999);
            $user->otp = $otp;

            $user->save();
            $user->sendOTPNotification($otp);
            DB::commit();
            return $this->response->status(true, 200)->massage(__("OTP Sent To verification Your Mail"))->returnJson();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function VerifyOTPCode(UserRequest $request)
    {
       //dd(Carbon::now());
        DB::beginTransaction();
        try {
            $VerifiedUser = $this->repository->VerifyOTP($request->validated());

            if (!$VerifiedUser) {
                return $this->response->status(false, 400)->massage(__("Ops Worng OTP Or Expired"))->returnJson();
            }
            DB::commit();
            return $this->response->status(true, 200)->massage(__("Your Email Has Verification"))->returnJson();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function logout()
    {
        auth()->logout();

        return $this->response->status(true, 200)->massage(__("Successfully Logged Out"))->returnJson();
    }
}
