<?php

namespace App\Models;

use App\Interfaces\ModelInterface as ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Book.
 *
 */
class Book extends Model implements ModelInterface {

    use SoftDeletes;

    protected $guarded = ['id'];
    public $table = 'book_master';
    public $timestamps = false;

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeActive($query) {
        $query->where(function ($query) {
            $query->where('status', '1');
        });
        return $query;
    }

    public function getProductQtyAttribute() {
        $qty = 1;
        if($this->pivot->quantity > 0){
            $qty = $this->pivot->quantity;
        }
        return $qty;
    }

}
