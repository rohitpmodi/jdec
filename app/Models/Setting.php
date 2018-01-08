<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class Setting extends Model
{
    public $table = 'settings';
    public $fillable = ['settings', 'lang'];
}
