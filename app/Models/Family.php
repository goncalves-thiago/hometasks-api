<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    use HasFactory;

    protected $table = 'families';

    protected $fillable = [
        'name',
        'owner_id',
        'allowance'
    ];

    // Many to Many relationship with User model
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // One to Many relationship with User model
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // One to Many relationship with Task model
    public function tasks() : HasMany 
    {
        return $this->hasMany(Task::class);
    }
}
