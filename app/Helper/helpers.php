<?php

if (!function_exists('gratavarUrl')) {

    /**
     * Gravatar URL from Email address.
     *
     * @param string $email   Email address
     * @param string $size    Size in pixels
     * @param string $default Default image [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $rating  Max rating [ g | pg | r | x ]
     *
     * @return string
     */
    function gravatarUrl($email, $size = 60, $default = 'mm', $rating = 'g') {
        return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . "?s={$size}&d={$default}&r={$rating}";
    }

}

function strip_tags_content($text) {
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
}

function getUserID() {
    if (Sentinel::check()) {

        return Sentinel::getUser()->id;
    } else {
        return;
    }
}

/**
 * Backend menu active.
 *
 * @param $path
 * @param string $active
 *
 * @return string
 */
function setActive($path, $active = 'active') {
    if (is_array($path)) {
        foreach ($path as $k => $v) {
            $path[$k] = getLang() . '/' . $v;
        }
    } else {
        $path = getLang() . '/' . $path;
    }

    return call_user_func_array('Request::is', (array) $path) ? $active : '';
}

/**
 * @return mixed
 */
function getLang() {
    return LaravelLocalization::getCurrentLocale();
}

/**
 * @param null $url
 *
 * @return mixed
 */
function langURL($url = null) {

    //return LaravelLocalization::getLocalizedURL(getLang(), $url);

    return getLang() . $url;
}

/**
 * @param $route
 * @param array $parameters
 *
 * @return mixed
 */
function langRoute($route, $parameters = []) {
    return URL::route(getLang() . '.' . $route, $parameters);
}

/**
 * @param $route
 *
 * @return mixed
 */
function langRedirectRoute($route) {
    return Redirect::route(getLang() . '.' . $route);
}

function FlashAlert($title = null, $message = null) {
    $flash = app('App\Http\FlashAlert');
    if (func_num_args() == 0) {
        return $flash;
    }

    return $flash->info($title, $message);
}

function getStates() {
    $states = array();
    $state_list = \App\Models\State::orderBy('name', 'asc')->get()->toArray('name', 'code');
    foreach ($state_list as $row) {
        $states[$row['code']] = $row['name'];
    }
    $states[''] = 'Select State';
    ksort($states);
    return $states;
}

function getShippingAccount() {
    $account = array();

    $account[''] = 'Select Account';
    $account['FedEx'] = 'FedEx';
    $account['UPS'] = 'UPS';
    $account['OTHER'] = 'OTHER';
    //ksort($account);
    return $account;
}

function getTiers() {
    $states = array();
    $state_list = \App\Models\Tier::orderBy('id', 'asc')->get()->toArray('id', 'title');
    foreach ($state_list as $row) {
        $tiers[$row['id']] = $row['title'];
    }
    $tiers[''] = 'Select Tier';
    ksort($tiers);
    return $tiers;
}

function formatDollar($val) {
    if (!$val) {
        $val = 0;
    }
    return '$' . number_format(floatval(preg_replace('/[\$,]/', '', $val)), 2, '.', ',');
}

function removeDollarFormat($val) {
    $val = trim(str_replace(',', '', str_replace('$', '', $val)));
    return number_format($val, 2);
}

function getIpSubnetMarkFromEmail($email) {
    $domain_name = preg_replace('/.+@/', '', $email);
    $ip = gethostbyname($domain_name);
    if ($domain_name == $ip) {
        return $domain_name;
    } else {
        $dotPosition = strrpos($ip, '.');
        $ip_subnet = trim(substr($ip, 0, $dotPosition));
        return $ip_subnet;
    }
}

function processText($string, $class = '') {
    $string = trim($string);
    if (!$string) {
        return '';
    }
    if ($string != strip_tags($string)) {
        return $string;
    } else {
        return '<p class="' . $class . '">' . $string . '</p>';
    }
}

function getSubMenuDropdown() {
    return null;
}

function formatDate($date, $format = 'd/m/Y', $current_time = false) {
    if (empty($date)) {
        if ($current_time == true) {
            return date($format);
        } else {
            return '';
        }
    }
    $date = date('Y-m-d', strtotime($date));
    if ($date != '0000-00-00') {
        return date($format, strtotime($date));
    } else {
        return '';
    }
}

function calculatePercentage($num, $percentage) {
    if ($percentage > 0) {
        $total = $num + $num * ($percentage / 100);
        $total = number_format((float) $total, 2, '.', '');
        return $total;
    } else {
        return $num;
    }
}

function getStatus($status_db) {
    switch ($status_db) {
        case '1':
            $status = 'Enabled';
            break;
        case '0':
            $status = 'Disabled';
            break;
        default:
            $status = 'Enabled';
    }
    return $status;
}

function getPreviousPurchaseText($product_id) {
    $text = '';
    $user = App\Models\User::find(Sentinel::getUser()->id);
    if ($user->orders()->count() > 0) {
        $orders = $user->orders()->orderBy('id', 'desc')->get();
        foreach ($orders as $order) {
            $order_id = $order->id;
            $order = \App\Models\Order::find($order_id);
            if ($order->product()->where('product_id', $product_id)->count() > 0) {
                $order_date = date('d F Y', strtotime($order->order_date));
                $text = 'You have previously purchased this product on ' . $order_date . '.';
                return $text;
            }
        }
    }
    return $text;
}

function numberWithDecimal($number) {
    if (empty($number)) {
        $number = 0;
    }
    return number_format($number, 2, '.', '');
}

function getUserShippingState() {
    $shipping_state = '';
    if (Sentinel::check()) {
        $user = \App\Models\User::find(Sentinel::getUser()->id);
        if ($user->address()->where('address_type', 'shipping')->count() > 0) {
            $shipping_address = $user->address()->where('address_type', 'shipping')->get();
            $shipping_state = @$shipping_address[0]->state;
        }
    }
    return $shipping_state;
}

function userState($address_type = 'shipping') {
    $state = '';
    if (Sentinel::check()) {
        $user = \App\Models\User::find(Sentinel::getUser()->id);
        if ($user->address()->where('address_type', $address_type)->count() > 0) {
            $address = $user->address()->where('address_type', $address_type)->get();
            $state = @$address[0]->state;
        }
    }
    return $state;
}

function getUserAddress($address_type) {
    $address = array();
    if (Sentinel::check()) {
        $user = App\Models\User::find(Sentinel::getUser()->id);
        if ($user->address()->where('address_type', $address_type)->count() > 0) {
            $address = $user->address()->where('address_type', $address_type)->orderBy('id', 'desc')->get();
            $address = $address[0];
        }
    }
    return $address;
}

function getStateDropdown() {
    $state = \App\Models\State::orderBy('name', 'asc')->lists('name', 'name')->toArray();
    $state = [null => 'STATE'] + $state;
    return $state;
}

function inWords($number) {
    $ns = array(1 => 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve');
    $str = @$ns[$number];
    if (empty($str)) {
        $str = $number;
    }
    if ($number > 1) {
        $str .= ' products';
    } else {
        $str .= ' product';
    }
    return $str;
}

function getCartCount() {
    $count = 0;
    if (Sentinel::check()) {
        $count = \App\Models\Cart::where(['user_id' => Sentinel::getuser()->id])->get()->count();
        if ($count == 0) {
            $count = count(Session::get('product'));
        }
    }
    return $count;
}

function srNo($key, $per_page = 50) {
    if (app('request')->input('page')) {
        $page = app('request')->input('page');
    } else {
        $page = 1;
    }
    $sr_no = ($per_page * ($page - 1)) + $key + 1;
    return $sr_no;
}

function calculateBasePrice($mrp, $tax) {
    if (!$mrp) {
        return 0;
    }
    if ($tax == 0) {
        return $mrp;
    }
    $base_price = ($mrp * 100) / (100 + $tax);
    return $base_price;
}

function getProductItemHighestTax($product_id) {
    $product = new App\Models\Product();
    $highest_tax = $product->getProductItemHighestTax($product_id);
    return $highest_tax;
}

function parseIndianDate($date, $format = 'Y-m-d') {
    if (!$date) {
        return NULL;
    }
    $date_exp = explode('/', $date);
    $date = $date_exp[1] . '/' . $date_exp[0] . '/' . $date_exp[2];
    return date('Y-m-d', strtotime($date));
}

function addProductToCart($product_id, $preferred_delivery_date = NULL) {
    $cartdata = array(
        'user_id' => Sentinel::getuser()->id,
        'product_id' => $product_id,
        'preferred_delivery_date' => $preferred_delivery_date
    );
    $existing_cart = \App\Models\Cart::where('user_id', Sentinel::getuser()->id)->where('product_id', $product_id)->first();
    if($existing_cart){
        if($preferred_delivery_date){
            $existing_cart->preferred_delivery_date = $preferred_delivery_date;
        }
        $existing_cart->save();
    }else{
        $cart = \App\Models\Cart::create($cartdata);
    }
}
function updateCartPreferredDeliveryDate($preferred_delivery_date = NULL){
    if($preferred_delivery_date){
        $update_data = array('preferred_delivery_date' => $preferred_delivery_date);
        App\Models\Cart::where('user_id',Sentinel::getuser()->id)->update($update_data);
    }
}
function deleteFromCart($id){
    $cart = \App\Models\Cart::where('user_id', Sentinel::getuser()->id)->where('id', $id)->first();
    if($cart){
        $cart->delete();
    }
}
function getPreferredDeliveryDate($product_id){
    $cart = \App\Models\Cart::where('user_id', Sentinel::getuser()->id)->where('product_id', $product_id)->first();
    return $cart->preferred_delivery_date;
}