<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 27.11.2017
 * Time: 4:15 PM
 */

namespace App\Comments;

use \App\Http\Controllers\Controller as Controller;
use App\Response\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class  CommentsController extends Controller
{

    protected $commentsGateway;

    public function __construct(CommentsGateway $commentsGateway)
    {
        $this->commentsGateway = $commentsGateway;
    }

    public function show(Request $request, $entity)
    {

        $comments = $this->commentsGateway->getEntityComments($entity);

        return ApiResponse::makeResponse($comments);

    }

    public function store(Request $request){

        $this->commentsGateway->addComments(1,'siema',1);
    }

    public function destroy(Request $request, $entityId){

    }



}