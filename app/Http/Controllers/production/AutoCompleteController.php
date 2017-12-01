<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 29.11.2017
 * Time: 2:40 AM
 */


namespace App\Users;

use App\Entity\EntityGateway;
use App\Exception\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use Validator;
use Illuminate\Support\Facades\Auth;


class AutoCompleteController extends Controller
{

    protected $usersGateway;

    public function __construct(UsersGateway $usersGateway)
    {
        $this->usersGateway = $usersGateway;
//        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
//        $user = Auth::user();
        $userName = $request->get('userName');


        $result = $this->usersGateway->searchUserAC($userName);

        return json_encode($result);

    }


//    public function index()
//    {
//
//        $rating = $this->ratingGateway->getEntitiesRating();
//
//        return $rating;
//
//    }


}
