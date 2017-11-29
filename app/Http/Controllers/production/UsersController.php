<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 10/16/17
 * Time: 6:53 PM
 */

namespace App\Users;

use \App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class  UsersController extends Controller
{

    protected $usersGateway;

    public function __construct(UsersGateway $usersGateway)
    {
        $this->usersGateway = $usersGateway;

    }

    public function index()
    {

        return 1;

    }

    public function show(Request $request, $users)
    {


        $receiver = $this->usersGateway->getUser($users)[0];

        return json_encode(['user' => $receiver]);

    }


}