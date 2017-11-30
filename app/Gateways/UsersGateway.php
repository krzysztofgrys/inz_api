<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 28.11.2017
 * Time: 6:11 PM
 */


namespace App\Users;

use Illuminate\Database\Eloquent\Model;
use App\Exception\ApiException;
use DB;

class UsersGateway extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';


    public function getUser($id)
    {
        $query = $this->select('*')->where('id', '=', $id)->get();

        return $query;
    }

    public function getUserByName($name){
        $query = $this->select('id', 'name', 'avatar')->where('name', '=', $name)->get();

        return $query;

    }


    public function updateUserData(){


    }


    public function searchUser($userName){

        $query= $this->select('name')-> where('name', 'like', $userName)->get()->pluck('name');

        return $query;

    }
}