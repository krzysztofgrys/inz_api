<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 9/26/17
 * Time: 9:55 PM
 */

class ApiResponse extends \Illuminate\Http\Response
{


    public static function makeResponse($data)
    {
        $response = Response::json($data);
        $response->header('Content-Type', 'application/json');

        return $response;

    }
}