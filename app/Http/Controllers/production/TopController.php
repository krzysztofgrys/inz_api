<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 11/16/17
 * Time: 6:14 PM
 */

namespace App\Top;

use App\Entity\EntityGateway;

use App\Exception\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Validator;

class TopController extends Controller
{

    protected $entityGateway;
    protected $response;


    public function __construct(EntityGateway $entityGateway)
    {
        $this->entityGateway = $entityGateway;
    }


    public function index(Request $request){

        $input = [
            'limit' => $request->get('limit', 6)
        ];

        $rules = [
            'limit'    => 'required|numeric|between:1,24',
        ];

        if(Validator::make($input, $rules)->fails()){
            Throw new ApiException(400);
        }

        $data = $this->entityGateway->getTopEntities($input['limit']);

        return ApiResponse::makeResponse($data);
    }


    public function show(Request $request, $limit){

        $input = [
            'limit' => $request->get('limit', 6)
        ];

        $rules = [
            'limit'    => 'required|numeric|between:1,24',
        ];

        if(Validator::make($input, $rules)->fails()){
            Throw new ApiException(400);
        }

        $data = $this->entityGateway->getTopEntities($limit);

        return ApiResponse::makeResponse($data);
    }
}