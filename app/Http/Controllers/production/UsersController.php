<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 10/16/17
 * Time: 6:53 PM
 */

namespace App\Users;

use App\Entity\EntityGateway;
use \App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Response\ApiResponse;

class  UsersController extends Controller
{

    protected $usersGateway;
    protected $entityGateway;

    public function __construct(UsersGateway $usersGateway, EntityGateway $entityGateway)
    {
        $this->usersGateway  = $usersGateway;
        $this->entityGateway = $entityGateway;

    }

    public function index()
    {
        return 1;
    }

    public function show(Request $request, $user)
    {
        $user         = $this->usersGateway->getUser($user)[0];
        $userEntities = $this->entityGateway->getUserEntities($user->id);

        $response = [
            'user'          => $user,
            'user_entities' => $userEntities
        ];

        return ApiResponse::make($response);
    }

    public function update(Request $request, $user)
    {


        $city        = $request->get('city', '');
        $description = $request->get('description', '');
        $fullname    = $request->get('fullname', '');

        $user = $this->usersGateway->editUser($user, $city, $description, $fullname);

        return $user;
    }
}