<?php
namespace App\Helpers;
class ApiResponse
{
    public static function sendResponse($data , $message = null, $status)
    {
        //check if the data is a rsource collection and paginated
        if (
            $data instanceof \Illuminate\Http\Resources\Json\ResourceCollection &&
            $data->resource instanceof \Illuminate\Contracts\Pagination\Paginator
        ) {
            $original = $data->response()->getData(true);

            return response()->json([
                'message' => $message,
                'data' => $original['data'] ?? [],
                'meta' => $original['meta'] ?? null,
                'links' => $original['links'] ?? null,
            ], $status);
        }
       
        return response()->json([

            'message' => $message,
            'data' => $data,
        ],$status);
    }

}