<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 03.10.17
 * Time: 08:52
 */

namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiException extends \Exception implements HttpExceptionInterface, \JsonSerializable
{
    private $statusCode;
    private $headers;
    protected $message = null;
    protected $apiErrorCode;
    protected $detailMessage = null;

    public function __construct(
        $statusCode, $messageCode = null, $subString = null, $apiErrorCode = null, \Exception $previous = null, array $headers = array(), $code = 0
    ) {
        if (func_num_args() == 2) {
            if (is_numeric($messageCode)) {
                list($apiErrorCode, $messageCode) = array($messageCode, null);
            } else {
                if ($messageCode instanceof \Exception) {
                    list($previous, $messageCode) = array($messageCode, null);
                }
            }
        } else {
            if (func_num_args() == 3) {
                if ($subString instanceof \Exception && is_string($messageCode)) {
                    list($previous, $subString) = array($subString, null);
                } else {
                    if (is_numeric($messageCode) && $subString instanceof \Exception) {
                        list($apiErrorCode, $previous, $messageCode, $subString) = array($messageCode, $subString, null, null);
                    }
                }
            } else {
                if (func_num_args() == 4) {
                    if (is_numeric($subString)) {
                        list($apiErrorCode, $previous, $subString, $apiErrorCode) = array($subString, $apiErrorCode, null, null);
                    } else {
                        if (is_array($subString) && $apiErrorCode instanceof \Exception) {
                            list($previous, $apiErrorCode) = array($apiErrorCode, null);
                        }
                    }
                }
            }
        }
        $this->statusCode = $statusCode;
        $this->headers    = $headers;
        if (is_numeric($apiErrorCode)) {
            $this->apiErrorCode = $apiErrorCode;
        }
        if (!empty($messageCode) && \Lang::has('httpstatus.' . $messageCode)) {
            if (empty($subString)) {
                $this->message = \Lang::get('httpstatus.' . $messageCode);
            } else {
                $this->message = \Lang::get('httpstatus.' . $messageCode, $subString);
            }
        } else {
            if (\Lang::has('httpstatus.' . $statusCode)) {
                $this->message = \Lang::get('httpstatus.' . $statusCode);
            }
        }

        parent::__construct($this->message, $statusCode, $previous);
    }

    /**
     * Function to get the statusCode.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Function to get the headers.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Function to get the ApiErrorCode.
     */
    public function getApiErrorCode()
    {
        return $this->apiErrorCode;
    }

    /**
     * Function to get the detailMessage.
     */
    public function getDetailMessage()
    {
        if (!empty($this->apiErrorCode) && \Lang::has('httpstatus.' . $this->apiErrorCode)) {
            $this->message = \Lang::get('httpstatus.' . $this->apiErrorCode);
        }

        return $this->detailMessage;
    }

    public function getDocUrlReference()
    {
        if (!empty($this->apiErrorCode)) {
            $href = \Config::get('api.api_error_doc_url') . '#' . $this->apiErrorCode; // $e->getApiErrorCode();
        } else {
            $href = \Config::get('api.api_error_doc_url') . '#' . $this->statusCode;
        }
        return $href;
    }

    /**
     * Function to return a concatenated string made from
     * the variables set in the object.
     *
     * @return string
     */
    public function prettyPrint()
    {
        $response['header']
                           =
            __CLASS__ . "[{$this->code}]:[{$this->statusCode}]:[{$this->message}]:{$this->getMessage()} at {$this->getFile()}({$this->getLine()})";
        $response['stack'] = "{$this->__toString()}";
        return $response;
    }


    protected function publishAttributes()
    {
        $x = new \stdClass();
        $x->status = $this->getStatusCode();
        $x->message = $this->getMessage();
        $x->code = $this->apiErrorCode;
        $x->info = $this->getDetailMessage();
        $x->more_info = config('api.api_error_doc_url') . '#' . $this->getStatusCode();
        $x->metadata = (object)[
            'links' => (object)[
                'rel'  => 'self',
                'href' => config('app.url') . isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
            ]
        ];
        return $x;
    }


    public function jsonSerialize()
    {
        return $this->publishAttributes();
    }

    public function xmlSerialize()
    {
        return $this->publishAttributes();
    }
}