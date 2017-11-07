<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 9/14/17
 * Time: 11:01 PM
 */

namespace App\Tags;

use Illuminate\Database\Eloquent\Model;
use App\Exception\ApiException;

class TagsGateway extends Model
{
    protected $table = 'tags';

    public function getTags()
    {
        $query = self::all();

        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function getTag($tag)
    {
        $query = self::select('*')->where('name', '=', $tag)->get();
        if (empty($query)) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function addTag($tag, $description = '')
    {
        $this->checkIfTagExist($tag);

        $query = self::insert(
            [
                'name'        => $tag,
                'description' => $description,
                'owner_id'    => 0
            ]);


    }


    private function checkIfTagExist($tag)
    {
        $query = self::where('name', $tag)->first();

        if ($query !== null) {
            throw new ApiException('500');
        }

        return true;
    }

}