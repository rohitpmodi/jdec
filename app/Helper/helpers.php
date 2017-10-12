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
    return array('' => '', 'menu_qnique' => 'Sewing Machines', 'menu_hand' => 'Hand Quilting', 'menu_machine' => 'Machine Frames', 'menu_qct' => 'Automation', 'menu_contact' => 'Contact');
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