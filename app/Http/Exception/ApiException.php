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
    protected $message = '';

    public function __construct($statusCode, $message, $headers = [], $previous = null)
    {

        $this->statusCode = $statusCode;
        $this->message    = $message;
        $this->headers    = $headers;


        parent::__construct($this->message, $statusCode, $previous);
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