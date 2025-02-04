<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\SoftDeletes as EloquentSoftDeletes;

class Ticket extends Model
{

    use EloquentSoftDeletes;
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
