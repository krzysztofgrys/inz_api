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
        $query = self::select('*')
            ->where('isDeleted', false)
            ->get();

//        if ($query->isEmpty()) {
//            throw new ApiException(404, '404_no_content');
//        }

        return $query;
    }

    public function getEntity($entity)
    {
        $query = $this->join('users', 'entity.user_id', '=', 'users.id')->
        select('users.id AS user_id',
            'entity.id AS entity_id',
            'users.name as user_name',
            'entity.title as title',
            'entity.thumbnail as thumbnail',
            'entity.description as description'
        )
            ->where('entity.id', '=', $entity)
            ->where('isDeleted', false)
            ->get();


        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function addEntity($user, $title, $description, $thumbnail, $selectedType, $url, $own)
    {
        $this->user_id     = $user;
        $this->title       = $title;
        $this->description = $description;
        $this->thumbnail   = $thumbnail;
        $this->url         = $url;
        $this->save();


    }

    public function getLatestId()
    {
        return self::all()->last()->id;
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


        $query = self::leftJoin('entity_ratings', 'entity_id', 'entity.id')->
        select(
            'entity.id',
            'entity.user_id',
            'title',
            'description',
            'thumbnail',
            'url',
            'own',
            'selected_type',
            'isEdited',
            'isDeleted',
            'entity.created_at',
            'entity.updated_at',
            DB::raw('count(entity_ratings.entity_id) as rating')
        )
            ->where('entity.created_at', '>', $carbon)
            ->where('isDeleted', false)
            ->groupBy('entity.id')
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
            'own',
            'selected_type',
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
            'own',
            'selected_type',
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
}

