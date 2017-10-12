<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Name of route
	|--------------------------------------------------------------------------
	|
	| Enter the routes name to enable dynamic imagecache manipulation.
	| This handle will define the first part of the URI:
	|
	| {route}/{template}/{filename}
	|
	| Examples: "images", "img/cache"
	|
	 */

	'route' => 'imagecache',

	/*
	|--------------------------------------------------------------------------
	| Storage paths
	|--------------------------------------------------------------------------
	|
	| The following paths will be searched for the image filename, submited
	| by URI.
	|
	| Define as many directories as you like.
	|
	 */

	'paths' => [
		'storage/uploads/images',
		public_path('uploads'),
		public_path('uploads/article'),
		public_path('uploads/users'),
		public_path('uploads/products'),
		public_path('uploads/products/loop'),
		public_path('uploads/products/thumbs'),
		public_path('uploads/products/related'),
		public_path('uploads/products/widget'),
		public_path('uploads/products/gallery_thumb/')
	],

	/*
	|--------------------------------------------------------------------------
	| Manipulation templates
	|--------------------------------------------------------------------------
	|
	| Here you may specify your own manipulation filter templates.
	| The keys of this array will define which templates
	| are available in the URI:
	|
	| {route}/{template}/{filename}
	|
	| The values of this array will define which filter class
	| will be applied, by its fully qualified name.
	|
	 */

	'templates' => [
		'small'      => 'Intervention\Image\Templates\Small',
		'medium'     => 'Intervention\Image\Templates\Medium',
		'large'      => 'Intervention\Image\Templates\Large',
		'blackwhite' => 'App\Http\Filters\BlackWhiteFilter',
		'df'         => 'App\Http\Filters\DemoFilter ',
		'hpb'        => 'App\Http\Filters\BlogLoopFilter',
		'shop'       => 'App\Http\Filters\ShopFilter',
		'product'    => 'App\Http\Filters\ProductFilter',
		'widget'    => 'App\Http\Filters\WidgetFilter'
	],

	/*
	|--------------------------------------------------------------------------
	| Image Cache Lifetime
	|--------------------------------------------------------------------------
	|
	| Lifetime in minutes of the images handled by the imagecache route.
	|
	 */

	'lifetime' => 43200

];