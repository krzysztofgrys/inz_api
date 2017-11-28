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


class EntityGateway extends Model
{
    protected $table = 'entity';

    public function getEntities()
    {
        $query = self::all();

//        if ($query->isEmpty()) {
//            throw new ApiException(404, '404_no_content');
//        }

        return $query;
    }

    public function getEntity($entity)
    {
        $query = $this->join('users', 'entity.user_id', '=', 'users.id')->
        select('*')->where('entity.id', '=', $entity)->get();

        if (empty($query)) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function addEntity($user, $title, $description, $thumbnail, $selectedType, $url, $own)
    {
        $query = self::insert(
            [
                'user_id'     => $user,
                'title'       => $title,
                'description' => $description,
                'thumbnail'   => $thumbnail,
                'url'         => $url,
                'own'         => $own,
            ]);
    }

    public function getLatestId()
    {
        return self::all()->last()->id;
    }


    public function getTopEntities($limit)
    {

        $carbon = Carbon::now(-$limit);

        $query = self::select('*')->where('created_at', '>', $carbon)->get();

        if (empty($query)) {
            throw new ApiException(404, '404_no_content');
        }


    }
}