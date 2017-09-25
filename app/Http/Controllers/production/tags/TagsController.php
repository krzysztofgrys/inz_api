<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 9/14/17
 * Time: 11:00 PM
 */

namespace App\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EllipseSynergie\ApiResponse\Contracts\Response;


class TagsController extends Controller{

    protected $tagsGateway;
    protected $response;

    public function __construct(TagsGateway $tagsGateway, Response $response)
    {
        $this->response = $response;
        $this->tagsGateway = $tagsGateway;
    }

    public function index(){

        $tags = $this->tagsGateway->getTags();

        return $this->response->withCollection($tags,)
        return json_encode($tags,JSON_PRETTY_PRINT);
    }

    public function show(Request $request, $tag){



        $tag = $this->tagsGateway->getTag($tag);

        return $this->response->withItem($tag, new BookTrans);
    }

}