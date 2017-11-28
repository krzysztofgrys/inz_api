<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 28.11.2017
 * Time: 9:35 PM
 */

namespace App\Ratings;

use Illuminate\Database\Eloquent\Model;
use App\Exception\ApiException;
use Carbon\Carbon;
use DB;


class RatingGateway extends Model
{
    protected $table = 'entity_ratings';


    public function rateEntity($entityId, $userId)
    {

        $this->entity_id = $entityId;
        $this->user_id   = $userId;
        $this->save();

        return true;

    }

    public function getEntityRating($entityId)
    {

        $query = self::where('entity_id', '=', $entityId)->count();

        return $query;
    }

    public function getEntitiesRating()
    {

        $query = DB::table('entity_ratings')->selectRaw(
            'entity_id, COUNT(*) 
           ')->groupBy('entity_id')->get()->keyBy('entity_id');

       return $query;
    }
}
