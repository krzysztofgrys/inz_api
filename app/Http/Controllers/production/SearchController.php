<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 28.11.2017
 * Time: 1:41 AM
 */


namespace App\Search;


use App\Entity\EntityGateway;
use App\Http\Controllers\Controller;
use App\Users\UsersGateway;
use Illuminate\Http\Request;


class SearchController extends Controller
{


    protected $entityGateway;
    protected $usersGateway;

    public function __construct(EntityGateway $entityGateway, UsersGateway $usersGateway)
    {
        $this->entityGateway = $entityGateway;
        $this->usersGateway  = $usersGateway;
    }

    public function show(Request $request, $show)
    {
        $type    = $request->get('type');
        $users   = [];
        $entries = [];

        switch ($type) {
            case 'entry':
                $entries = $this->entityGateway->searchEntities($show);
                break;

            case 'profile':
                $users = $this->usersGateway->searchUser($show);
                break;

            default:
                return json_encode([]);
        }

        $result = [
            'users'   => $users,
            'entries' => $entries
        ];

        return json_encode($result);

    }

}