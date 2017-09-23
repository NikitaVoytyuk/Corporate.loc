<?php

Route::resource('/', 'IndexController', [
                                            'only' => ['index'],
                                            'names' => ['index' => 'home'],
                                        ]);

Route::resource('portfolios', 'PortfolioController', [
                                                    'param'=> [
                                                                'portfolios'=>'alias',
                                                              ]
                                                     ]);

Route::resource('articles', 'ArticlesController', [
                                                      'param'=> [
                                                                 'articles'=>'alias',
                                                                ]
                                                     ]);

Route::get('articles/cat/{cat_alias?}',['uses'=>'ArticlesController@index', 'as'=>'articlesCat'])->where('cat_alias', '[\w-]+');

Route::resource('comment', 'CommentController', ['only' => ['store']]);

Route::match(['get', 'post'], '/contacts', ['uses'=>'ContactsController@index', 'as'=>'contacts']);

Route::get('login',['uses' => 'Auth\LoginController@showLoginForm', 'as'=>'login' ]);

Route::post('login','Auth\LoginController@login');

Route::get('register',['uses' => 'Auth\RegisterController@showRegistrationForm', 'as' => 'register']);

Route::post('register','Auth\RegisterController@register');

Route::get('logout','Auth\LoginController@logout');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function (){
    Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'adminIndex']);
    Route::resource('/articles', 'Admin\ArticlesController', ['as' => 'admin']);
    Route::resource('/permissions', 'Admin\PermissionsController', ['as' => 'admin']);
    Route::resource('/menus', 'Admin\MenusController', ['as' => 'admin']);
    Route::resource('/portfolios', 'Admin\PortfoliosController', ['as' => 'admin']);
});




