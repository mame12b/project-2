<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->room->type == 'group'){
            $receiver = [];
            foreach($this->receiver() as $rec){
                $receiver[] = new UserResource($rec);
            }
        }else if($this->room->type == 'single'){
            $receiver = new UserResource($this->receiver());
        }else{
            $receiver = null;
        }

        return [
            'id' => $this->id,
            'room_id' => $this->message_room_id,
            'sender_type' => $this->sender_type,
            'sender' => new UserResource($this->sender),
            'receiver' => $receiver,
            'text' => $this->text,
            'created_at' => $this->created_at,
        ];
    }
}
