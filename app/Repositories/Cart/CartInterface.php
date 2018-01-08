<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Price;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Sentinel;
use Session;

/**
 * Interface CartInterface.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
interface CartInterface extends RepositoryInterface
{
    /**
     * @param $slug
     *
     * @return mixed
     */
    public function getBySlug($slug, $isPublished = false);

    public static function getCartInfo(&$sections, &$cart, &$total)
    {
        if (Sentinel::check()) {
            $cart = Auth::user()->cart;
        } else {
            $cart = new Collection();
            if (Session::has('cart')) {
                foreach (Session::get('cart') as $item) {
                    $elem = new Cart();
                    $elem->product_id = $item['product_id'];
                    $elem->amount = $item['qty'];
                    $elem->price_id = $item['price_id'];
                    if (isset($item['options'])) {
                        $elem->options = $item['options'];
                    }
                    $cart->add($elem);
                }
            }
        }
        $total = 0;
        foreach ($cart as $item) {
            $pric = Price::find($item->price_id)->final_price;
             
                if ($item->sub_product) {
                    $sub_prd = ProductSubProducts::find($item->sub_product);

                    $prods_price = Product::find($sub_prd->sub_product_id)->prices->first();
                    
                    $pric+=(floatval(preg_replace('/[\$,]/', '', Price::find($prods_price->id)->final_price)) * $sub_prd->quantity);
                }
            $total += $pric * $item->amount;
        }
    }
}
