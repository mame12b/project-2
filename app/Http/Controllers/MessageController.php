<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageRoomResource;
use App\Models\Message;
use App\Models\MessageRoom;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * get room messages
     *
     * @param string $room_id
     */
    public function getRoomMessage(string $room_id): array
    {
        /**
         * get room
         *
         * @var \App\Models\MessageRoom $room
         */
        $room = MessageRoom::find($room_id);

        // check if room is exist
        if(empty($room)) return ['error' => 'invalid room id'];

        /**
         * room message list
         *
         * @var \App\Http\Resources\MessageResource $data
         */
        $data = [];

        foreach($room->messages as $message ){
            $data[] = new MessageResource($message);
        }

        return $data;
    }

    /**
     * send message to user from department/internship
     *
     * @param string $room_id
     * @param \Illuminate\Http\Request $request
     */
    public function sendDepartmentMessage(Request $request, string $room_id)
    {
        // check request
        $request->validate([
            'text' => 'string|required'
        ]);

        /**
         * get room
         *
         * @var \App\Models\MessageRoom $room
         */
        $room = MessageRoom::find($room_id);

        // check if room is exist
        if(empty($room)) return ['error' => 'invalid room id'];

        // creating message
        $message = new Message([
            'message_room_id' => $room->id,
            'sender_type' => 'Internship',
            'sender_id' => $room->internship_id,
            'text' => $request->text
        ]);

        if($message->save()){
            return new MessageResource($message);
        }else{
            return  ['error' => 'unknown'];
        }
    }

    /**
     * send message to department/internship from user
     *
     * @param string $room_id
     * @param \Illuminate\Http\Request $request
     */
    public function sendUserMessage(Request $request, string $room_id)
    {
        // check request
        $request->validate([
            'text' => 'string|required'
        ]);

        /**
         * get room
         *
         * @var \App\Models\MessageRoom $room
         */
        $room = MessageRoom::find($room_id);

        // check if room is exist
        if(empty($room)) return ['error' => 'invalid room id'];

        // creating message
        $message = new Message([
            'message_room_id' => $room->id,
            'sender_type' => 'User',
            'sender_id' => auth()->user()->id,
            'text' => $request->text
        ]);

        if($message->save()){
            return new MessageResource($message);
        }else{
            return  ['error' => 'unknown'];
        }
    }

    /**
     * delete message
     *
     * @param string $message_id
     */
    public function deleteMessage(string $message_id)
    {
        /**
         * get message data
         *
         * @var \App\Models\Message $message
         */
        $message = Message::find($message_id);

        // check if message is exist
        if(empty($message)) return ['error' => 'invalid message id'];

        // for latter sending
        $data_m = new MessageResource($message);

        if($message->delete()){
            return [
                'status' => 200,
                'data' => $data_m
            ];
        }else{
            return [
                'status' => 500,
                'message' => 'something want wrong'
            ];
        }
    }

    /**
     * get room
     *
     * @param string $room_id
     */
    public function getRoom(string $room_id)
    {
        /**
         * get room
         *
         * @var \App\Models\MessageRoom $room
         */
        $room = MessageRoom::find($room_id);

        // check if room is exist
        if(empty($room)) return ['error' => 'invalid room id'];

        return new MessageRoomResource($room);
    }

    /**
     * get group room members
     */
}
