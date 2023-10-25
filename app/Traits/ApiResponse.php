<?php

namespace App\Traits;

trait ApiResponse
{
    public function success($data = null , $message = 'Request Was Fully Successfully' , $code = 200){
        return response([
            'data' => $data,
            'message' => $message
        ] , $code);
    }
    public function error($message = 'Request Failed' , $code){
        return response([
            'message' => $message
        ] , $code);
    }
}
