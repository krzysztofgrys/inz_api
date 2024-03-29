<?php

namespace App\Exceptions;

use App\Response\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        $headers                                = method_exists($exception,
            'getHeaders') && !empty($exception->getHeaders()) ? $exception->getHeaders() : $request->headers->all();
        $headers['Access-Control-Allow-Origin'] = '*';

        $code = (method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 200);

        return ApiResponse::encode($exception, $code, $headers);
    }
}
