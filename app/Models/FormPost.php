<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FormPost.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class FormPost extends Model
{
    public $table = 'form_posts';
    public $fillable = ['sender_name_surname', 'sender_email', 'sender_phone_number', 'subject', 'message'];
}
