<?php

/**
 * Created by PhpStorm.
 * User: Rohit Modi
 * Date: 1/26/2017
 * Time: 10:53 AM
 */
// THIS PART IS THE LOOP MACRO
GoogleTagManager::macro('impression', function ($product) {
    GoogleTagManager::set('ecommerce', [
        'currencyCode' => 'USD',
        'detail' => [
            'products' => [ $product->getGoogleTagManagerData()]
        ]
    ]);
});


// AFTER LOOP IS SETUP CORRECT WE PUT THIS IN THE PRODUCTCONTROLLER SHOW METHOD
// GoogleTagManager::impression($product);

GoogleTagManager::macro('impression', function ($product) {
    GoogleTagManager::set(
            'ecommerce', [
        'currencyCode' => 'USD',
        'impressions' => [
            'products' => [ $product->getImpressionData()]
        ],
        'detail' => [
            'products' => [ $product->getDetailData()]
        ]
    ]);
});

GoogleTagManager::macro('impression1', function ($product_arr, $show_details = true) {
    $impression_data = array();
    foreach ($product_arr as $parr) {
        $product = \App\Models\Product::where('id', $parr['id'])->first();
        $get_impression = $product->getImpressionData();
        $get_impression['list'] = $parr['list'];
        $get_impression['position'] = $parr['position'];
        $impression_data[] = $get_impression;
    }
    if ($show_details === true) {
        $detail_product = $product_arr[0];
        $product = \App\Models\Product::where('id', $detail_product['id'])->first();
        foreach ($impression_data as $k => $v){
            if($v['id'] == @$product->prices[0]->sku){
                unset($impression_data[$k]);
            }
        }
        $impression_data = array_values($impression_data);
        GoogleTagManager::set(
                'ecommerce', [
            'currencyCode' => 'USD',
            'impressions' => $impression_data,
            'detail' => [
                'products' => [ $product->getDetailData()]
            ]
        ]);
    } else {
        GoogleTagManager::set(
                'ecommerce', [
            'currencyCode' => 'USD',
            'impressions' => $impression_data
        ]);
    }
});
