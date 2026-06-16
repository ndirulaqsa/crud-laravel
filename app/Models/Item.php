<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'status',
        'reporter_name',
        'contact',
        'reported_at',
        'photo_url',
    ];
}
