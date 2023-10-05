<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    /**
     * Common method to return API response
     *
     * @param string $message
     * @param array $content
     * @param int $status
     *
     * @return  \Illuminate\Http\Response
     */
    public function jsonResponse(string $message = null, array $content = [], $status = Response::HTTP_OK)
    {
        $final_response = [];
        $final_response['Status'] = 'Failure';

        if (strlen($message) > 0) {
            $final_response['Message'] = $message;
        }
        if (count($content) > 0) {
            $final_response = array_merge($final_response,$content);
        }
        if (substr($status, 0, 2) == 20) {
            $final_response['Status'] = 'Success';
        }
        $this->logResponse($final_response);
        return response()->json($final_response, $status);
    }

    /**
     * Log response detail
     *
     * @param array $response
     * @return void
     */
    public function logResponse($response)
    {
        $msg    =   "Hit Request Url:- " . request()->getRequestUri() . " with request data:- " . json_encode(request()->all());
        $msg    .=  " return response:- " . json_encode($response) . " at " . getCurrentTimeForLog();
        if ($response['Status'] == "Success") {
            //Log::info($msg);
        } else {
            $log = new Log();
            Log::error($msg);
        }
    }
}
