<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message_room_id', 'sender_type', 'sender_id','text', 'file_path', 'file_extension', 'file_name','seen'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get the Room that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(MessageRoom::class, 'message_room_id', 'id')->withTrashed();
    }

    /**
     * Get the Sender that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): ?BelongsTo
    {
        if($this->sender_type === 'Internship'){
            return $this->belongsTo(Internship::class, 'sender_id', 'id')->withTrashed();
        }else if($this->sender_type === 'User'){
            return $this->belongsTo(User::class, 'sender_id', 'id')->withTrashed();
        }else{
            return null;
        }
    }

    /**
     * Get receiver of the message
     *
     * @return \App\Models\Internship|\User|array<\App\Models\User>|null
     */
    public function receiver(): mixed
    {
        // check if room is group
        if($this->room->type == 'group'){
            /**
             * message receivers
             *
             * @var array|null $rec
             */
            $rec = null;
            if($this->sender_type === 'Internship'){
                foreach($this->room->groupMember as $user){
                    $rec[] = $user->user;
                }
            }else if($this->sender_type === 'User'){
                foreach($this->room->groupMember as $user){
                    if($this->sender_id != $user->user_id){
                        $rec[] = $user->user;
                    }
                }
                $rec[] = $this->room->internship;
            }else{
                return null;
            }

            return $rec;
        }
        // check if room is single
        else if($this->room->type == 'single'){
            if($this->sender_type === 'Internship'){
                return User::find($this->room->user_id);
            }else if($this->sender_type === 'User'){
                return Internship::find($this->room->internship_id);
            }else{
                return null;
            }
        }
        else {
            return null;
        }
    }


}
