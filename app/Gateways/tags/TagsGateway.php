<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 9/14/17
 * Time: 11:01 PM
 */

namespace App\Tags;

use Illuminate\Database\Eloquent\Model;

class TagsGateway extends Model
{
    protected $table = 'tags';

    public function getTags()
    {
        $query = self::select('name')->get();

        if($query->isEmpty()){
            return 404;
        }
        return $query;
    }

    public function getTag($tag){
        $query = self::select('name')->where('name','=',$tag)->get();

        if($query->isEmpty()){
            return 404;
        }

        return $query;
    }

}