<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internship extends Model
{
    use HasFactory, SoftDeletes, GenerateAvatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id', 'title', 'description', 'minimum_cgpa', 'quota', 'deadline', 'start_date', 'end_date', 'status', 'avatar'
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
        static::deleted(function ($internship) {
            $internship->userApplications()->get()->each->delete();
            $internship->prerequisites()->get()->each->delete();

        });

        static::created(function ($internship) {
            $internship->generateAvatar($internship->title);
        });
    }

    /**
     * Get the Department that owns the Internship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id')->withTrashed();
    }

    /**
     * Get all of the Prerequisite for the Internship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prerequisites(): HasMany
    {
        return $this->hasMany(InternshipPrerequisite::class, 'internship_id', 'id');
    }

    /**
     * Get all of the User Applications for the Internship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userApplications(): HasMany
    {
        return $this->hasMany(UserApplication::class, 'internship_id', 'id');
    }

    /**
     * check if the deadline is passe
     *
     * @return bool
     */
    public function isDeadlinePassed(): bool
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->deadline)->isPast();
    }

    /**
     * update status
     *
     * @return bool
     */
    public function updateStatus(int $status = null): bool
    {
        if($status==null){
            if($this->isDeadlinePassed()){
                if($this->status == 1 || $this->status == 3 || $this->status == 2 || $this->status == 0){
                    if(!$this->isEnded() && !$this->isStarted()){
                        $this->update(['status' => 3]);
                    }
                }

                if($this->isEnded() && $this->status == 3){
                    $this->update(['status' => 4]);
                }
            }else{
                $this->update(['status' => 1]);
            }
        }
        return true;
    }

    /**
     * check if the internship is started
     *
     * @return bool
     */
    public function isStarted(): bool
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->start_date)->isPast();
    }

    /**
     * check if the internship is ended
     *
     * @return bool
     */
    public function isEnded(): bool
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->end_date)->isPast();
    }

    /**
     * check if the internship is ended
     *
     * @return bool
     */
    public function isQuotaFull(): bool
    {
        if ($this->quota == null) return true;
        return ($this->quota - $this->statistics()['acceptedApplications'] <= 0);
    }

    /**
     * fetch all accepted applications related to this internship
     *
     * @return array <string, int|bool>
     */
    public function statistics(): array
    {
        /**
         * flag
         *
         * @var array<string, int> $flag
         */
        $flag = [
            'applicationCount' => ($this->userApplications) ? count($this->userApplications) : 0,
            'pendingApplications' => 0,
            'acceptedApplications' => 0,
            'rejectedApplications' => 0
        ];
        if($this->userApplications){
            foreach($this->userApplications as $userApplication){
                if($userApplication->status == 0){
                    $flag['pendingApplications']++;
                }else if($userApplication->status == 1){
                    $flag['acceptedApplications']++;
                }else if($userApplication->status == 2){
                    $flag['rejectedApplications']++;
                }
            }
        }
        return $flag;
    }

    /**
     * get all interns under this internship
     *
     * @return array<\App\Models\User>
     */
    public function getInterns(): array
    {
        /**
         * get all accepted applications
         *
         * @var \App\Models\UserApplication $userApplications
         */
        $userApplications = UserApplication::where('internship_id', $this->id)->where('status', '1')->get();

        /**
         * get all users profile
         *
         * @var array<\App\Models\User> $users
         */
        $users = [];

        foreach($userApplications as $userApplication){
            $users[] = $userApplication->user;
        }

        return $users;
    }

    /**
     * create message group which include all the interns
     *
     * @return int|bool
    */
    public function createGroup(): int|bool
    {
        if($this->status == '2'){

            /**
             * create new group room
             *
             * @var \App\Models\MessageRoom $room
             */
            $room = new MessageRoom([
                'internship_id' => $this->id,
                'user_id' => null,
                'type' => 'group'
            ]);

            if($room->save()){
                // register all users to group
                foreach($this->getInterns() as $intern){
                    (new MessageRoomGroup([
                        'message_room_id' => $room->id,
                        'user_id' => $intern->id,
                    ]))->save();
                }

                return $room->id;
            }

            return false;

        }

        return false;
    }
}
