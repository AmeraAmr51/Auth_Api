<?php

namespace App\Http\Repositories\User;

use App\Helpers\ApiResponse;
use App\Http\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(ApiResponse $response, User $model)
    {
        parent::__construct($model);
        $this->response = $response;
    }

    public function VerifyOTP($request)
    {
        
        $user = User::where('id', $request['id'])->where('created_at','>',Carbon::now()->subMinutes(5))->first();
        if(!$user || $user->OTP != $request['otp']){
            return false;
        }
        $user=User::where('id', $request['id'])->update(['email_verified_at' => now()]);
        return $user;
    }
}
