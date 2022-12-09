<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageRoom extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type', 'internship_id', 'user_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get all of the Messages for the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'message_room_id', 'id');
    }

    /**
     * Get all of the Group Members for the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupMember(): HasMany
    {
        return $this->hasMany(MessageRoomGroup::class, 'message_room_id', 'id');
    }

    /**
     * Get the Internship that owns the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class, 'internship_id', 'id')->withTrashed();
    }

    /**
     * Get the User that owns the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    /**
     * check if the group has the given member
     *
     * @param string|int $user_id
     * @return bool
     */
    public function hasMember(string|int $user_id): bool
    {

        foreach($this->groupMember as $member){
            if($member->user_id == $user_id){
                return true;
            }
        }

        return false;
    }
}
