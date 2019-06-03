<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Param;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function outWithError($message = 'Error', $status_code = 400, $errors = null)
    {
        return $this->out([], $message, $status_code, $errors);
    }

    public function out($data = [], $message = 'Success', $status_code = 200, $errors = null)
    {
        $response = [
            "http_code"        => $status_code,
            "message"          => $message,
            "method"           => request()->method(),
            "base_url"         => config('app.url'),
            "uri"              => ltrim(request()->getRequestUri(), '/'),
            "query_parameters" => request()->query(),
            "errors"           => $errors,
            "data"             => $data

        ];

        return response()->json($response, $status_code);
    }
}
