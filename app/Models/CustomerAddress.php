<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    public $fillable = ['user_id','fname','lname','email','mobile','country_id','address','apartment','city','state','zip','notes'];
}
