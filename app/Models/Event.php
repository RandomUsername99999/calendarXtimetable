<?php

namespace App\Models; // Or just "namespace App;" if it's not in the Models folder

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'start', 'end'];
}