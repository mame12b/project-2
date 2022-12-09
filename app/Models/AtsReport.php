<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtsReport extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner','owner_type','start_date','end_date','file_path'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get the user that owns the UserApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getOwner(): mixed
    {
        if($this->owner_type == 'admin'){
            return $this->belongsTo(User::class, 'owner', 'id')->withTrashed();
        }else if($this->owner_type == 'school'){
            return $this->belongsTo(School::class, 'owner', 'id')->withTrashed();
        }else if($this->owner_type == 'department'){
            return $this->belongsTo(Department::class, 'owner', 'id')->withTrashed();
        }else{
            return null;
        }
    }
}
