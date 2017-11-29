<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 28.11.2017
 * Time: 1:43 AM
 */

namespace App\Messages;

use Illuminate\Database\Eloquent\Model;
use App\Exception\ApiException;
use DB;

class MessagesGateway extends Model
{

    protected $table      = 'messages';
    protected $primaryKey = 'id';

    public function getConversations($from)
    {
        $query = $this->join('user_messages', 'messages.id', '=', 'user_messages.message_id')
            ->join('users', 'user_messages.receiver_id', '=', 'users.id')->
            selectRaw('users.id, users.name, users.avatar')
            ->where('user_messages.receiver_id', '=', $from)
            ->orWhere('user_messages.sender_id', '=', $from)
            ->groupBY('user_messages.receiver_id', 'user_messages.sender_id', 'users.id', 'users.name', 'users.avatar')
            ->get();

        return $query;
    }

    public function getMessages($sender, $receiver)
    {
        $query = $this->join('user_messages', 'messages.id', '=', 'user_messages.message_id')->
        select('*')
            ->whereIn('user_messages.receiver_id', [$receiver, $sender])
            ->whereIn('user_messages.sender_id', [$receiver, $sender])
            ->get();

        return $query;
    }


    public function sendMessage($sender, $receiver, $message)
    {
        $this->message = $message;
        $this->save();
        $id = $this->id;


        DB::table('user_messages')->
        insert([
                'sender_id'   => $sender,
                'receiver_id' => $receiver,
                'message_id'  => $id
            ]
        );

    }


}