<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 11/12/17
 * Time: 7:19 PM
 */

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use App\Exception\ApiException;
use Carbon\Carbon;
use DB;


class EntityGateway extends Model
{
    protected $table = 'entity';

    public function getEntities()
    {
        $query = $this->join('users', 'entity.user_id', '=', 'users.id')->
        leftJoin('entity_comments', 'entity.id', '=', 'entity_comments.entity_id')->
        select(
            'entity.id as id',
            'users.id as user_id',
            'entity.description as description',
            'entity.title as title',
            'entity.thumbnail as thumbnail',
            'entity.url as url',
            'users.name as user_name',
            'entity.created_at as created_at',
            DB::raw('count(entity_comments.entity_id) as comments')
        )
            ->where('isDeleted', false)
            ->groupBy('entity.id', 'users.id')
            ->orderBy('entity.created_at', 'desc')
            ->get();

        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function getEntity($entity)
    {
        $query = $this->join('users', 'entity.user_id', '=', 'users.id')->
        select('entity.id as id',
            'users.id as user_id',
            'entity.description as description',
            'entity.title as title',
            'entity.thumbnail as thumbnail',
            'entity.url as url',
            'users.name as user_name',
            'entity.isEdited as edited',
            'entity.created_at as created_at'
        )
            ->where('entity.id', '=', $entity)
            ->where('isDeleted', false)
            ->get();
        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function addEntity($user, $title, $description, $thumbnail, $url)
    {
        $this->user_id     = $user;
        $this->title       = $title;
        $this->description = $description;
        $this->thumbnail   = $thumbnail;
        $this->url         = $url;
        $this->save();

        return $this->id;
    }


    public function getTopEntities($limit)
    {

        $carbon = Carbon::now();

        if (is_numeric($limit)) {
            $carbon = $carbon->subHour($limit);
        } else {

            switch ($limit) {
                case 'week':
                    $carbon = $carbon->subWeek(1);

                    break;

                case 'year':
                    $carbon = $carbon->subYear(1);

                    break;
                case 'month':
                    $carbon = $carbon->subMonth(1);

                    break;
                case 'all':
                    $carbon = $carbon->subYears(100);
                    break;
            }


        }


        $query = $this->join('users', 'entity.user_id', '=', 'users.id')->
        leftJoin('entity_ratings', 'entity_id', 'entity.id')->
        select(
            'entity.id',
            'entity.user_id',
            'users.name as user_name',
            'title',
            'entity.description',
            'entity.thumbnail',
            'url',
            'isEdited',
            'isDeleted',
            'entity.created_at',
            'entity.updated_at',
            DB::raw('count(entity_ratings.entity_id) as rating')
        )
            ->where('entity.created_at', '>', $carbon)
            ->where('isDeleted', false)
            ->groupBy('entity.id', 'users.name')
            ->orderBy('rating', 'desc')
            ->get();

        if (empty($query)) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }


    public function getUserEntities($userId)
    {

        $query = self::leftJoin('entity_ratings', 'entity_id', 'entity.id')->
        select(
            'entity.id',
            'entity.user_id',
            'title',
            'description',
            'thumbnail',
            'url',
            'isEdited',
            'isDeleted',
            'entity.created_at',
            'entity.updated_at',
            DB::raw('count(entity_ratings.entity_id) as rating')
        )
            ->where('entity.user_id', $userId)
            ->where('isDeleted', false)
            ->groupBy('entity.id')
            ->get();


        return $query;
    }

    public function searchEntities($string)
    {
        $query = self::leftJoin('entity_ratings', 'entity_id', 'entity.id')->
        select(
            'entity.id',
            'entity.user_id',
            'title',
            'description',
            'thumbnail',
            'url',
            'isEdited',
            'isDeleted',
            'entity.created_at',
            'entity.updated_at',
            DB::raw('count(entity_ratings.entity_id) as rating')
        )
            ->where('entity.title', 'like', "%$string%")
            ->where('isDeleted', false)
            ->orWhere('entity.description', 'like', "%$string%")
            ->groupBy('entity.id')
            ->get();

        return $query;
    }

    public function deleteEntity($entityId, $id)
    {
        $entity = $this->find($entityId);
        if ($entity->user_id != $id) {
            return false;
        }
        $entity->isDeleted = true;
        $entity->save();

        return true;
    }


    public function editEntity($id, $title, $description, $url, $thumbnail)
    {
        $entity = $this->find($id);

        $entity->title       = $title;
        $entity->description = $description;
        $entity->url         = $url;
        $entity->isEdited    = true;

        if ($thumbnail != '') {
            $entity->thumbnail = $thumbnail;
        }

        $entity->save();

        return $id;
    }


}

