<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 03.10.17
 * Time: 08:52
 */

namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiException extends \Exception implements HttpExceptionInterface
{
    private   $statusCode;
    private   $headers;
    protected $message       = null;
    protected $apiErrorCode;
    protected $detailMessage = null;

    public function __construct($statusCode, $message)
    {

        $this->statusCode = $statusCode;
        $this->message    = $message;


        parent::__construct($this->message, $statusCode);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}