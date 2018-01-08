<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class BaseModel extends Model
{
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'LIKE', "%$search%")
                    ->where('lang', getLang())
                    ->orWhere('content', 'LIKE', "%$search%");
        });
    }
}
