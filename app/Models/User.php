<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
   use HasApiTokens, HasFactory, Notifiable, SoftDeletes, GenerateAvatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'last_login',
        'type',
        'is_staff',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * cache delete event to delete child models
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function ($user) {
            $user->information()->get()->each->delete();
        });

        static::created(function ($user) {
            $user->generateAvatar($user->getName());
        });
    }

    /**
     * Get the department associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'head_id', 'id');
    }

    /**
     * Get the school associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function school(): HasOne
    {
        return $this->hasOne(School::class, 'head_id', 'id');
    }

    /**
     * Get all of the applications for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(UserApplication::class, 'user_id', 'id');
    }

    /**
     * Get the information associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function information(): HasOne
    {
        return $this->hasOne(UserInformation::class, 'user_id', 'id');
    }

    /**
     * Interact with the user's type.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return new Attribute(
            get: fn($value) => ["user", "admin", "school", "department"][$value],
        );
    }

    /**
     * Get user Full Name (if there is) or user name from email address
     *
     * @return string
     */
    public function getName(): string
    {
        // name || email
        $data = "";

        if(!empty($this->information->first_name)) $data .= $this->information->first_name;
        if(!empty($this->information->middle_name)) $data .= ' ' . $this->information->middle_name;
        if(!empty($this->information->last_name)) $data .= ' ' . $this->information->last_name;

        if(empty($data)){
            $data = explode('@',$this->email)[0];
        }

        return $data;
    }

    /**
     * check if the user have already an internship
     *
     * @return bool
     */
    public function haveInternship(): bool
    {
        if($this->applications){
            /**
             * flag to check the status
             *
             * @var bool $flag
             */
            $flag = false;
            foreach($this->applications as $application){
                if($application->status == 1){
                    $flag = true;
                    break;
                }
            }
            return $flag;
        }else{
            return false;
        }
    }

    /**
     * check if user is eligible to apply to internships (generally)
     *
     * @param \App\Models\Internship $internship
     * @return array
     */
    public function isEligibleToApply(Internship $internship): array
    {
        /**
         * errors list
         *
         * @var array $errors
         */
        $errors = [];
        if($this->information){

            if(!$this->information->first_name){
                $errors[] = [ 'You didn\'t fill First Name!' ];
            }
            if(!$this->information->middle_name){
                $errors[] = [ 'You didn\'t fill Middle Name!' ];
            }
            if(!$this->information->student_id){
                $errors[] = [ 'You didn\'t fill Student Id!' ];
            }
            if(!$this->information->cgpa){
                $errors[] = [ 'You didn\'t fill Cumulative GPA!' ];
            }
            if(!$this->information->university){
                $errors[] = [ 'You didn\'t fill University!' ];
            }
            if(!$this->information->application_letter_file_path){
                $errors[] = [ 'You didn\'t upload application letter!' ];
            }
            if(!$this->information->application_acceptance_file_path){
                $errors[] = [ 'You didn\'t upload application acceptance form!' ];
            }
            if(!$this->information->student_id_file_path){
                $errors[] = [ 'You didn\'t upload student id!' ];
            }
            if($this->information->cgpa < $internship->minimum_cgpa){
                $errors[] = [ 'Your Cumulative GPA is lower then the minimum required Cumulative GPA' ];
            }
        }else{
            $errors[] = [ 'Your don\'t have any information filled, please fill first!' ];
        }


        return $errors;
    }

    /**
     * check if user is eligible to apply to internships (generally)
     *
     * @param \App\Models\Internship $internship
     * @return bool
     */
    public function alreadyApplied(Internship $internship): bool
    {
        if($this->applications){
            /**
             * flag to check the status
             *
             * @var bool $flag
             */
            $flag = false;
            foreach($this->applications as $application){
                if($application->internship_id == $internship->id){
                    $flag = true;
                    break;
                }
            }
            return $flag;
        }else{
            return false;
        }
    }

    /**
     * profile Completeness
     *
     * @return float
     */
    public function profileCompleteness(): float
    {
        /**
         * flag
         *
         * @var int $flag
         */
        $flag = 0;

        /**
         * lookup
         *
         * @var array<string> $lookup
         */
        $lookup = [
            "id",
            "user_id",
            "created_at",
            "updated_at",
            "deleted_at"
        ];

        if($this->information){
            $getAttrs = $this->information->attributesToArray();

            foreach($getAttrs as $key => $attr){
                if(!in_array($key, $lookup)){
                    if($attr != null && !empty($attr) && $attr != '') $flag++;
                }
            }
        }

        $flag = ($flag / 15) * 100;
        return round($flag , 2);
    }

    /**
     * get message room
     *
     * @param int $internship_id
     * @return bool|int
     */
    public function getRoom(int $internship_id): int|bool
    {
       /**
        * get room
        *
        * @var \App\Models\MessageRoom $room
        */
        $room = MessageRoom::where('internship_id', $internship_id)->where('user_id', $this->id)->first();

        if($room){
            return $room->id;
        }else{
            return false;
        }
    }
}
