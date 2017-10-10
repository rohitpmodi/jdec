<?php

Route::any('form-submit', function() {
    var_dump(Input::file('file'));
});

// if(Config::get('app.debug'))
// {
//     array_push($middleware, ['middleware' => 'clearcache']);
// }
// Route::group($middleware, function() {

/*
  |--------------------------------------------------------------------------
  | MODEL BINDING INTO ROUTE
  |--------------------------------------------------------------------------
 */

//Route::model('article', 'App\Models\Article');
// Route::pattern('slug', '[a-z0-9- _]+');

/*
  |--------------------------------------------------------------------------
  | Frontend Routes
  |--------------------------------------------------------------------------
 */

$languages = LaravelLocalization::getSupportedLocales();
foreach ($languages as $language => $values) {
    $supportedLocales[] = $language;
}

$locale = Request::segment(1);
if (in_array($locale, $supportedLocales)) {
    LaravelLocalization::setLocale($locale);
    App::setLocale($locale);
}

Route::get('/', function () {
    return Redirect::to(LaravelLocalization::getCurrentLocale(), 302);
});

Route::group(['prefix' => LaravelLocalization::getCurrentLocale(), 'before' => ['localization', 'before']], function () {
    Session::put('my.locale', LaravelLocalization::getCurrentLocale());


    // filemanager
    Route::get('filemanager/show', function () {
        return View::make('backend/plugins/filemanager');
    })->before('sentinel.auth');


    // frontend dashboard
    Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

    // article
    Route::get('/resources/blog', ['as' => 'dashboard.article', 'uses' => 'ArticleController@index']);
    Route::get('/resources/blog/{slug}', ['as' => 'dashboard.article.show', 'uses' => 'ArticleController@show']);
    Route::get('/resources/blog/{id}', [
        'as' => 'dashboard.article.show',
        'uses' => 'ArticleController@bart'
    ]);


    // news
    Route::get('/resources/news', ['as' => 'dashboard.news', 'uses' => 'NewsController@index']);
    Route::get('/resources/news/{slug}', ['as' => 'dashboard.news.show', 'uses' => 'NewsController@show']);
    // video
    Route::get('/resources/video', ['as' => 'dashboard.video', 'uses' => 'VideoController@index']);
    Route::get('/resources/video/{slug}', ['as' => 'dashboard.video.show', 'uses' => 'VideoController@show']);

    // projects
    //Route::get('/resources/project', ['as' => 'dashboard.project', 'uses' => 'ProjectController@index']);
    //Route::get('/resources/project/{slug}', ['as' => 'dashboard.project.show', 'uses' => 'ProjectController@show']);
    // faq
    Route::get('/resources/faqs', ['as' => 'faqs', 'uses' => 'FaqController@show']);

    // tags
    Route::get('/tag/{slug}', ['as' => 'dashboard.tag', 'uses' => 'TagController@index']);

    // categories
    Route::get('/company/{slug}', ['as' => 'dashboard.company', 'uses' => 'CompanyController@index']);

    // page
    Route::get('/page', ['as' => 'dashboard.page', 'uses' => 'PageController@index']);
    Route::get('/page/{slug}', ['as' => 'dashboard.page.show', 'uses' => 'PageController@show']);

    // photo gallery
    Route::get('/photo-gallery/{slug}', [
        'as' => 'dashboard.photo_gallery.show',
        'uses' => 'PhotoGalleryController@show',
    ]);

    // contact
    Route::get('/contact', ['as' => 'dashboard.contact', 'uses' => 'FormPostController@getContact']);

    // rss
    Route::get('/rss', ['as' => 'rss', 'uses' => 'RssController@index']);

    // search
    Route::get('/search', ['as' => 'admin.search', 'uses' => 'SearchController@index']);

    // language
    // Route::get('/set-locale/{language}', array('as' => 'language.set', 'uses' => 'LanguageController@setLocale'));
    // maillist
    Route::get('/save-maillist', ['as' => 'frontend.maillist', 'uses' => 'MaillistController@getMaillist']);
    Route::post('/save-maillist', ['as' => 'frontend.maillist.post', 'uses' => 'MaillistController@postMaillist']);
});

/*
  |--------------------------------------------------------------------------
  | Backend Routes
  |--------------------------------------------------------------------------
 */

Route::group(['prefix' => LaravelLocalization::getCurrentLocale()], function () {
    Route::group([
        'prefix' => 'admin',
        'middleware' => ['before', 'sentinel.auth', 'sentinel.permission'],
            ], function () {
        Route::get('orders', ['as' => 'admin.orders.index', 'uses' => 'OrderController@index']);
        Route::get('orders/index', ['as' => 'admin.orders.index', 'uses' => 'OrderController@index']);
        Route::post('orders/store', ['as' => 'admin.orders.store', 'uses' => 'OrderController@store']);
        Route::get('orders/show', ['as' => 'admin.orders.show', 'uses' => 'OrderController@show']);
        Route::get('orders/{orders}/charge', ['as' => 'admin.orders.charge', 'uses' => 'OrderController@charge']);
        Route::get('orders/{orders}/fraud', ['as' => 'admin.orders.fraud', 'uses' => 'OrderController@fraud']);
        Route::get('orders/{orders}/genuine', ['as' => 'admin.orders.genuine', 'uses' => 'OrderController@genuine']);
        Route::get('orders/{orders}/chargePaypal', ['as' => 'admin.orders.chargePaypal', 'uses' => 'OrderController@chargePaypal']);
        Route::get('orders/create', ['as' => 'admin.orders.create', 'uses' => 'OrderController@create']);
        Route::put('orders/{orders}', ['as' => 'admin.orders.update', 'uses' => 'OrderController@update']);
        Route::patch('orders/update/{orders}', ['as' => 'admin.orders.update', 'uses' => 'OrderController@update']);
        Route::delete('orders/{orders}', ['as' => 'admin.orders.destroy', 'uses' => 'OrderController@destroy']);
        Route::get('orders/{orders}/show', ['as' => 'admin.orders.show', 'uses' => 'OrderController@show']);
        Route::get('orders/{orders}/download', ['as' => 'admin.orders.download', 'uses' => 'OrderController@download']);
        Route::get('orders/{orders}/edit', ['as' => 'admin.orders.edit', 'uses' => 'OrderController@edit']);
    });
    Route::group([
        'prefix' => '/admin',
        'namespace' => 'Admin',
        'middleware' => ['before', 'sentinel.auth', 'sentinel.permission'],
            ], function () {
        Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'DashboardController@index']);


        Route::get('user', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
        Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
        Route::post('user/store', ['as' => 'admin.user.store', 'uses' => 'UserController@store']);
        Route::get('user/show', ['as' => 'admin.user.show', 'uses' => 'UserController@show']);

        Route::get('user/create', ['as' => 'admin.user.create', 'uses' => 'UserController@create']);

        Route::put('user/{user}', ['as' => 'admin.user.update', 'uses' => 'UserController@update']);
        Route::patch('user/update/{user}', ['as' => 'admin.user.update', 'uses' => 'UserController@update']);
        Route::delete('user/{user}', ['as' => 'admin.user.destroy', 'uses' => 'UserController@destroy']);
        Route::get('user/{user}/show', ['as' => 'admin.user.show', 'uses' => 'UserController@show']);
        Route::get('user/{user}/edit', ['as' => 'admin.user.edit', 'uses' => 'UserController@edit']);

        Route::get('company', ['as' => 'admin.company', 'uses' => 'CompanyController@index']);
        Route::get('company/create', ['as' => 'admin.company.create', 'uses' => 'CompanyController@create']);
        Route::delete('company/destroy/{id}', ['as' => 'admin.company.destroy', 'uses' => 'CompanyController@destroy']);
        Route::get('company/show/{id}', ['as' => 'admin.company.show', 'uses' => 'CompanyController@show']);
        Route::get('company/edit/{id}', ['as' => 'admin.company.edit', 'uses' => 'CompanyController@edit']);
        Route::patch('company/update/{company}', ['as' => 'admin.company.update', 'uses' => 'CompanyController@update']);
        
        Route::get('/company/{id}/delete', 'CompanyController@delete');
        Route::get('/company/{id}/show', 'CompanyController@show');
        Route::post('/company/create', 'CompanyController@store');
        Route::post('/company/{id}/edit', 'CompanyController@edit');
    });
});

Route::post('/contact', ['as' => 'dashboard.contact.post', 'uses' => 'FormPostController@postContact'], ['before' => 'csrf']);

// filemanager
Route::get('filemanager/show', function () {
    return View::make('backend/plugins/filemanager');
})->before('sentinel.auth');

// login
// Route::get('/admin/login',  ['as' => 'admin.login', function () {return View::make('backend/auth/login'); } ]);

Route::group(['namespace' => 'Admin'], function () {
    // admin auth
    Route::get('admin/logout', ['as' => 'admin.logout', 'uses' => 'AuthController@getLogout']);
    Route::get('admin/login', ['as' => 'admin.login', 'uses' => 'AuthController@getLogin']);
    Route::post('admin/login', ['as' => 'admin.login.post', 'uses' => 'AuthController@postLogin']);
    Route::post('admin/login', ['as' => 'login', 'uses' => 'AuthController@postLogin']);
    // admin password reminder
    Route::get('admin/forgot-password', ['as' => 'admin.forgot.password', 'uses' => 'AuthController@getForgotPassword']);
    Route::post('admin/forgot-password', ['as' => 'admin.forgot.password.post', 'uses' => 'AuthController@postForgotPassword']);
    Route::get('admin/{id}/reset/{code}', ['as' => 'admin.reset.password', 'uses' => 'AuthController@getResetPassword'])->where('id', '[0-9]+');
    Route::post('admin/reset-password', ['as' => 'admin.reset.password.post', 'uses' => 'AuthController@postResetPassword']);
});

//Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
//    Route::group(['prefix' => 'v1'], function () {
//        require 'api_routes.php';
//    });
//});
// });

Route::get('signin', ['as' => 'signin', 'uses' => 'AuthController@getSignin']);
Route::post('signin', 'AuthController@postSignin');
Route::post('signup', ['as' => 'signup', 'uses' => 'AuthController@postSignup']);



