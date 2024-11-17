<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'user_id',
        'title',
        'description',
        'deadline',
        'attachmentUrl',
        'status'
    ];

    protected $dates = ['deadline'];

    // One to many relationship with Family model
    public function family(): BelongsTo 
    {
        return $this->belongsTo(Family::class);
    }
   
    // One to many relationship with User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
