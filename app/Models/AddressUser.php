<?php

namespace App\Models;

use App\Interfaces\ModelInterface as ModelInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address.
 *
 */
class AddressUser extends Model implements ModelInterface {

    public $table = 'address_user';
    public $timestamps = false;

}
