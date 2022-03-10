<?php

namespace App\Helpers;

use App\Http\Requests\UserRequest;
use Illuminate\Routing\ResponseFactory;


class ApiResponse
{
    /**
     * @var ResponseFactory
     */
    private $response;
    /**
     * @var array
     */
    private $data;
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var string
     */
    private $message;
    /**
     * @var bool
     */
    private $status;

     public function __construct(ResponseFactory $response)
     {
         $this->response = $response;
         $this->status = true;
         $this->data =[];
         $this->statusCode=200;
         $this->message='';

     }

     public function setData(array $data): ApiResponse
     {
         $this->data = $data ;
         return $this;

     }

     public function status(bool $status , int $statusCode =null): ApiResponse
     {
         $this->status = $status;
         $this->statusCode=$statusCode;
         if($this->status == false && is_null($statusCode))
         {
             $this->statusCode =500;
         }
        
         
         return $this;
     }

     public function massage(string $massage): ApiResponse
     {
         $this->massage =$massage;
         return $this;

     }

     public function statusCode(int $statusCode)
     {
        $this->statusCode = $statusCode;
        return $this;

     }
     public function returnJson()
     {
         return $this->response->json([
             "massage"=>$this->massage ?? "",
             "data"=>$this->data ??[],
             "status"=>$this->status ?? true,
         ],$this->statusCode??200 );
     }



}