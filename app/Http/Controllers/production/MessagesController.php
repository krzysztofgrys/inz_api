<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 28.11.2017
 * Time: 1:41 AM
 */

namespace App\Messages;

use App\Exception\ApiException;
use App\Http\Controllers\Controller;
use App\Users\UsersGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Response\ApiResponse;
use Validator;

class MessagesController extends Controller
{

    protected $messagesGateway;
    protected $usersGateway;


    public function __construct(MessagesGateway $messagesGateway, UsersGateway $usersGateway)
    {
        $this->messagesGateway = $messagesGateway;
        $this->usersGateway    = $usersGateway;
        $this->middleware('auth:api');

    }


    public function index(Request $request)
    {

        $user = Auth::user()->id;

        $messages = $this->messagesGateway->getConversations($user);

        return ApiResponse::makeResponse($messages);

    }

    public function show($id)
    {

        $user     = Auth::user();
        $messages = $this->messagesGateway->getMessages($user->id, $id);
        $sender   = $user;
        $receiver = $this->usersGateway->getUser($id);


        $response = [
            'messages' => $messages,
            'sender'   => $sender,
            'receiver' => $receiver[0]
        ];

        return ApiResponse::makeResponse($response);

    }

    public function store(Request $request)
    {
        $user     = Auth::user();

        $receiver = $request->get('receiver');

        $receiver = $this->usersGateway->getUserByName($receiver)[0];

        $message = $request->get('message');

        $this->messagesGateway->sendMessage($user->id, $receiver->id, $message);

        return $receiver->id;
    }
}