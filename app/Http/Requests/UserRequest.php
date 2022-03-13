<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->path() == "api/register"){
        return [

            //
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required|min:8',
            

        ];
        }
        if($this->path() == "api/VerifyOTP"){
            return [
    
                //
                    'id'=>'required|integer|exists:users,id',
                    'otp'=>'required|string|min:4',
                
    
            ];
            }
            if($this->path() == "oauth/token"){
                return [
        
                    //
                        'id'=>'required|integer|exists:users,id',
                        'otp'=>'required|string|min:4',
                    
        
                ];
                }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => null
                ],
                400
            )
        );
    }
}
