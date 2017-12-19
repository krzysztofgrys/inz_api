<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 27.11.2017
 * Time: 4:35 PM
 */

namespace App\Comments;

use Illuminate\Database\Eloquent\Model;
use App\Exception\ApiException;
use DB;

class CommentsGateway extends Model
{

    protected $table      = 'comments';
    protected $primaryKey = 'id';


    public function getEntityComments($entity)
    {
        $query = $this->join('entity_comments', 'comments.id', '=', 'entity_comments.comments_id')->
        join('users', 'comments.user_id', '=', 'users.id')
            ->select(
                'comments.user_id as user_id',
                'comments.id as id',
                'users.name as name',
                'users.avatar as avatar',
                'comments.comments as comments',
                'comments.created_at as created_at',
                'comments.isEdited as edited'
            )
            ->where('entity_comments.entity_id', '=', $entity)
            ->where('comments.isDeleted', false)
            ->get();

        return $query;

    }


    public function addComments($entity, $comment, $user)
    {

        $this->user_id  = $user;
        $this->comments = $comment;
        $this->save();

        $id = $this->id;

        DB::table('entity_comments')->
        insert([
                'entity_id'   => $entity,
                'comments_id' => $id
            ]
        );
    }

    public function deleteComment($commentid)
    {

        $comment            = $this->find($commentid);
        $comment->isDeleted = true;
        $comment->save();

        return true;
    }

    public function editComment($id, $body)
    {
        $comment           = $this->find($id);
        $comment->isEdited = true;
        $comment->comments = $body;
        $comment->save();

        return true;
    }
}