<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersPhoneEmail extends Model
{
    protected $table= "users_phone_email";
    protected $fillable = [
         'phone_email'
     ];
    
    }