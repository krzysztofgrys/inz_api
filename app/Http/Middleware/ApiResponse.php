<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 03.10.17
 * Time: 08:09
 */

namespace App\Response;

use Response, Input;
use App\Exception\ApiException;

class ApiResponse extends Response
{

    public static function makeResponse($data, $nodeName = 'data')
    {

        $final[$nodeName] = $data;

        $response = Response::json($final, 200);
        $response->header('Content-Type', 'application/json');

        return $response;
    }


    public static function encode($data, $statusCode, $header)
    {
        try {
            $content = [
                'error' => [
                    'code'    => $data->getCode(),
                    'message' => $data->getMessage()
                ]
            ];

            $response = Response::json($content, $statusCode, $header);
            $response->header('Content-Type', 'application/json');

            return $response;

        } catch (ApiException $e) {
            $header = $e->getHeaders();

            $content = [
                'error' => [
                    'code'    => $e->getStatusCode(),
                    'message' => $e->getMessage()
                ]
            ];

            return Response::json($content, $e->getStatusCode(), $header);
        }
    }
}