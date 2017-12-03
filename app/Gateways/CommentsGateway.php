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
        select('*')->where('entity_comments.entity_id', '=', $entity)->where('comments.isDeleted', false)->get();


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

    public function deleteComment()
    {

    }

}