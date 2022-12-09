<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPrerequisiteResponse extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_application_id',
        'prerequisite_id',
        'response'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get the userApplication that owns the UserPrerequisiteResponse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userApplication()
    {
        return $this->belongsTo(UserApplication::class, 'user_application_id', 'id')->withTrashed();
    }

    /**
     * Get the internshipPrerequisite that owns the UserPrerequisiteResponse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function internshipPrerequisite(): BelongsTo
    {
        return $this->belongsTo(InternshipPrerequisite::class, 'prerequisite_id', 'id')->withTrashed();
    }
}
