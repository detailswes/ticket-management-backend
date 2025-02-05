<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\SoftDeletes as EloquentSoftDeletes;

class Ticket extends Model
{
    use EloquentSoftDeletes , HasApiTokens;

    protected $connection = 'mongodb';

    protected $attributes = [
        'status' => 'open', 
    ];
    
    protected $fillable = [
        'title',
        'description',
        'status',
    ];

}
