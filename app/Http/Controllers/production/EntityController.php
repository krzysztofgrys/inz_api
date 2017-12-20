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
use App\Ratings\RatingGateway;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
    protected $entityGateway;
    protected $ratingGateway;

    public function __construct(EntityGateway $entityGateway, RatingGateway $ratingGateway)
    {
        $this->middleware('auth:api', ['only' => ['store', 'destroy', 'update']]);
        $this->entityGateway = $entityGateway;
        $this->ratingGateway = $ratingGateway;
    }

    public function index()
    {
        $entities = $this->entityGateway->getEntities();
        $rating   = $this->ratingGateway->getEntitiesRating();
//

        $response = [];
        foreach ($entities as $entity) {

            $response1['id']          = $entity->id;
            $response1['user_id']     = $entity->user_id;
            $response1['title']       = $entity->title;
            $response1['description'] = $entity->description;
            $response1['thumbnail']   = $entity->thumbnail;
            $response1['rating']      = array_key_exists($entity->id, $rating) ? $rating[$entity->id]->count : 0;
            $response1['user_name']   = $entity->user_name;
            $response1['created_at']  = $entity->created_at;
            $response1['url']         = $entity->url;
            $response1['comments']    = $entity->comments;
            $response1['domain']      = parse_url($entity->url, PHP_URL_HOST);
            $response[]               = $response1;
        }

        return ApiResponse::makeResponse($response);
    }

    public function show(Request $request, $entity)
    {
        $entities  = $this->entityGateway->getEntity($entity);
        $rating    = $this->ratingGateway->getEntityRating($entity);
        $response1 = [];
        foreach ($entities as $entity) {

            $response1['id']          = $entity->id;
            $response1['user_id']     = $entity->user_id;
            $response1['title']       = $entity->title;
            $response1['description'] = $entity->description;
            $response1['thumbnail']   = $entity->thumbnail;
            $response1['is_edited']   = $entity->edited;
            $response1['rating']      = $rating;
            $response1['user_name']   = $entity->user_name;
            $response1['created_at']  = $entity->created_at->format('d.m.Y - H:i');
            $response1['url']         = $entity->url;
            $response1['domain']      = parse_url($entity->url, PHP_URL_HOST);
        }

        return ApiResponse::makeResponse($response1);
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
        $id   = $this->entityGateway->addEntity($user->id, $input['title'], $input['description'], $input['thumbnail'],
            $input['url']);

        return $id;
    }

    public function update(Request $request, $id)
    {

        $input = [
            'title'       => $request->get('title'),
            'description' => $request->get('description'),
            'thumbnail'   => $request->get('thumbnail'),
            'url'         => $request->get('url'),

        ];

        Auth::user();

        $entity = $this->entityGateway->editEntity($id, $input['title'], $input['description'], $input['url'], $input['thumbnail']);

        return ApiResponse::makeResponse($entity);
    }


    public function destroy($entityId)
    {
        $user = Auth::user();

        $entities = $this->entityGateway->deleteEntity($entityId, $user->id);

        if ($entities) {
            return 'ok';
        }

        return 'error';

    }


}