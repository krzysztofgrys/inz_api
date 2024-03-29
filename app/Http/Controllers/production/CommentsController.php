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
        $this->middleware('auth:api', ['only' => ['store', 'destroy', 'update']]);
    }

    public function show(Request $request, $entity)
    {
        $comments = $this->commentsGateway->getEntityComments($entity);

        return ApiResponse::makeResponse($comments);
    }

    public function store(Request $request)
    {

        $user    = Auth::user();
        $comment = $request->get('comment');
        $entity  = $request->get('entity');

        $this->commentsGateway->addComments($entity, $comment, $user->id);

        return $entity;
    }

    public function destroy(Request $request, $entityId)
    {
        $user = Auth::user();

        $result = $this->commentsGateway->deleteComment($request->get('comment'));

        return 'ok';

    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $result = $this->commentsGateway->editComment($request->get('comment_id'), $request->get('comment'));

        return 'ok';

    }


}