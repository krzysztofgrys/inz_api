<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 11/12/17
 * Time: 7:17 PM
 */

namespace App\Entity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
    protected $entityGateway;


    public function __construct(EntityGateway $entityGateway)
    {
        $this->middleware('auth:api', ['only' => ['store']]);
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
        $entities = $this->entityGateway->getEntity($entity);

        $response = [];
        foreach ($entities as $entity) {
            $response1['id']          = $entity->id;
            $response1['user_name']   = $entity->user_name;
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
            'title'         => $request->get('title'),
            'description'   => $request->get('description'),
            'thumbnail'     => $request->get('thumbnail'),
            'url'           => $request->get('url'),
            'own_input'     => $request->get('own_input'),
            'selected_type' => $request->get('selected_type')
        ];

        $rules = [
            'title'         => 'required|alpha|between:1,100',
            'description'   => 'required|alpha|between:1,360',
            'thumbnail'     => 'required|alpha',
            'selected_type' => 'required|alpha',
            'url'           => 'required_if:selected_type,ulr',
            'own_input'     => 'required_if:selected_type,own'
        ];


        $user = Auth::user();
        $this->entityGateway->addEntity($user->id, $input['title'], $input['description'], $input['thumbnail'], $input['selected_type'],
            $input['url'], $input['own_input']);

        return $this->entityGateway->getLatestId();
    }


}