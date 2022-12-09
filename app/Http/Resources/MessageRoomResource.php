<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userData = null;
        if($this->type == 'group'){
            $receiver = [];
            foreach($this->groupMember as $rec){
                if(auth()->user()->id == $rec->user->id) $userData = $rec->user;
                else $receiver[] = new UserResource($rec->user);
            }
        }else{
            $receiver = null;
        }

        return [
            'id' => $this->id,
            'internship' => new UserResource($this->internship),
            'user' => new UserResource($this->user ?? $userData),
            'receiver' => $receiver,
            'created_at' => $this->created_at
        ];
    }
}
