<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 9/14/17
 * Time: 11:00 PM
 */

namespace App\Tags;

use App\Exception\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Validator;



class TagsController extends Controller
{

    protected $tagsGateway;
    protected $response;

    public function __construct(TagsGateway $tagsGateway)
    {
        $this->tagsGateway = $tagsGateway;
    }

    public function index()
    {
        $tags = $this->tagsGateway->getTags();
        $response = [];
        foreach ($tags as $tag){
            $response1['id'] = $tag->id;
            $response1['name'] = $tag->name;
            $response1['owner'] = $tag->owner_id;
            $response1['href'] = $_SERVER['REQUEST_URI'].'/'.$tag->name;;
            $response[] = $response1;
        }

        return ApiResponse::makeResponse($response);
    }

    public function show(Request $request, $tag)
    {

        $tags = $this->tagsGateway->getTag($tag);

        $response = [];
        foreach ($tags as $tag){
            $response1['id'] = $tag->id;
            $response1['name'] = $tag->name;
            $response1['owner'] = $tag->owner_id;
            $response1['created_at'] = $tag->created_at;
            $response1['modified_at'] = $tag->modified_at;
            $response[] = $response1;
        }

        return ApiResponse::makeResponse($response);
    }

    public function store(Request $request) {

        $input = [
            'tag' => $request->get('tag')
        ];

        $rules = [
            'tag'    => 'required|alpha|between:1,50',
        ];

        if(Validator::make($input, $rules)->fails()){
           Throw new ApiException(400,'wrong_parameter');
        }

        $this->tagsGateway->addTag($input['tag']);


    }

}