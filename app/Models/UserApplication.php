<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApplication extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'internship_id',
        'status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * cache delete event to delete child models
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::deleted(function ($userApplication) {
            $userApplication->prerequisiteResponses()->get()->each->delete();
        });
    }

    /**
     * Get the user that owns the UserApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

     /**
     * Get the internship that owns the UserApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class, 'internship_id', 'id')->withTrashed();
    }

    /**
     * Get all of the prerequisiteResponses for the UserApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prerequisiteResponses(): HasMany
    {
        return $this->hasMany(UserPrerequisiteResponse::class, 'user_application_id', 'id');
    }

    /**
     * rank application by date
     *
     * @return int
     */
    public function rankByDate(): int
    {
        /**
         * flag
         *
         * @var int $flag
         */
        $flag = 1;

        /**
         * get all application with the same internship
         *
         * @var \App\Models\UserApplication $apps
         */
        $apps = UserApplication::where('internship_id', $this->internship_id)->orderBy('created_at', 'asc')->get();

        foreach($apps as $app){
            if($app->user_id === $this->user->id) break;
            $flag++;
        }
        return $flag;
    }

    /**
     * rank application by CGPA
     *
     * @return int
     */
    public function rankByCGPA(): int
    {
        /**
         * flag
         *
         * @var int $flag
         */
        $flag = 1;

        /**
         * get all application with the same internship
         *
         * @var \App\Models\UserApplication $apps
         */
        $apps = UserApplication::where('internship_id', $this->internship_id)->join('user_information', 'user_applications.user_id', '=', 'user_information.user_id')->orderBy('cgpa', 'desc')->get();

        foreach($apps as $app){
            if($app->user_id === $this->user->id) break;
            $flag++;
        }
        return $flag;
    }


    /**
     * check wether this application have interview or not
     *
     * @return int|bool
     */
    public function haveInterview(): int|bool
    {
        /**
         * get message room
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $rooms = MessageRoom::where('internship_id', $this->internship_id)->where('user_id', $this->user_id)->first();

        if(empty($rooms)){
            return false;
        }else{
            return $rooms->id;
        }
    }

    /**
     * delete user room permanently
     *
     * @return bool
     */
    public function deleteInterview(): bool
    {
        /**
         * get message room
         *
         * @var \App\Models\MessageRoom $rooms
         */
        $rooms = MessageRoom::where('internship_id', $this->internship_id)->where('user_id', $this->user_id)->first();

        if(empty($rooms)){
            return false;
        }else{
            $rooms->forceDelete();
            return true;
        }
    }

    /**
     * initialize message room
     *
     * @return int|bool
     */
    public function initInterview(): int|bool
    {
        // check if there is room
        if($this->haveInterview()) return false;

        // creating new room
        $room = new MessageRoom([
            'internship_id' => $this->internship_id,
            'user_id' => $this->user_id,
            'type' => 'single'
        ]);

        if($room->save()){
            return $room->id;
        }else{
            return false;
        }
    }
}
