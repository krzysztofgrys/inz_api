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
    protected $fillable   = ['city', 'description', 'fullname'];


    public function getUser($id)
    {
        $query = $this->select('*')->where('id', '=', $id)->get();

        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function getUserByName($name)
    {
        $query = $this->select('id', 'name', 'avatar')->where('name', '=', $name)->get();

        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;

    }

    public function searchUserAC($userName)
    {

        $query = $this->select('name')->where('name', 'like', "%$userName%")->get()->pluck('name');

        return $query;
    }

    public function getUserByEmail($email)
    {
        $query = $this->select('*')->where('email', '=', $email)->get();
        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function searchUser($userName)
    {
        $query = $this->select('name', 'avatar', 'id')->where('name', 'like', "%$userName%")->get();
        if ($query->isEmpty()) {
            throw new ApiException(404, '404_no_content');
        }

        return $query;
    }

    public function editUser($id, $city, $description, $fullname, $password, $c_password, $avatar)
    {
        $user = $this::find($id);

        $user->city        = $city;
        $user->description = $description;
        $user->fullname    = $fullname;
        if (!empty($password) && !empty($c_password)) {
            $user->password = bcrypt($password);
        }
        if ($avatar != '') {
            $user->avatar = $avatar;
        }
        $user->save();

        return $id;
    }
}