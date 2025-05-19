<?php
namespace App\Helpers;
class ApiResponse{
    public static function sendResponse($data=null,$message=null,$status=200){
        return response()->json([
            
            'message'=>$message,
            'data'=>$data,
        ]);
    }
    
}