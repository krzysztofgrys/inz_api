<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 11/12/17
 * Time: 7:17 PM
 */

namespace App\Entity;

use App\Exception\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Validator;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
    protected $entityGateway;
    protected $response;


    public function __construct(EntityGateway $entityGateway)
    {
        $this->entityGateway = $entityGateway;
    }

    public function index()
    {
        $entities = $this->entityGateway->getEntities();
        $response = [];
        foreach ($entities as $entity) {
            $response1['id']          = $entity->id;
            $response1['user_name']   = 'user';
            $response1['title']       = $entity->title;
            $response1['description'] = $entity->description;
            $response1['media']       = $entity->media;
            $response1['href']        = $_SERVER['REQUEST_URI'] . '/' . $entity->id;
            $response[]               = $response1;
        }

        return ApiResponse::makeResponse($response);
    }

    public function show(Request $request, $entity)
    {
        $user     = Auth::user();
        $entities = $this->entityGateway->getEntity($entity);
        $response = [];
        foreach ($entities as $entity) {
            $response1['id']          = $entity->id;
            $response1['user_name']   = $user->name;
            $response1['title']       = $entity->title;
            $response1['description'] = $entity->description;
            $response1['media']       = $entity->media;
            $response1['href']        = $_SERVER['REQUEST_URI'] . '/' . $entity->id;
            $response[]               = $response1;
        }

        return ApiResponse::makeResponse($response);
    }

    public function store(Request $request)
    {

        $input = [
            'title'       => $request->get('title'),
            'description' => $request->get('description'),
            'media'       => $request->get('media')
        ];

        $rules = [
            'title'       => 'required|alpha|between:1,50',
            'description' => 'required|alpha|between:1,50',
            'media'       => 'required|alpha',
        ];

//        if (Validator::make($input, $rules)->fails()) {
//            Throw new ApiException(400, 'wrong_parameter');
//        }


        $user = Auth::user();
        $this->entityGateway->addEntity($user->id, $input['title'], $input['description'], $input['media']);


        return $this->entityGateway->getLatestId();
    }


}