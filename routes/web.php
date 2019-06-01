<?php

use App\Http\Middleware\Nocache;


/*************************************************************
*	Route Générale de l'application
*************************************************************/
Route::get('/', 'AppController@index')->name('home');
Route::post('/check', 'AppController@passCheck')->name('passCheck');
Route::get('/sshtest', 'ServeurController@sshtest')->name('sshtest');
/*************************************************************/



/*************************************************************
*	Affichage des logs 
*************************************************************/
Route::get('/ips.log', 'AppController@ipslog')->name('ipslog');
Route::get('/internetbs.log', 'AppController@internetbslog')->name('internetbslog');
Route::get('/runscript.log', 'AppController@runscript')->name('internetbslog');
/*************************************************************/



/*************************************************************
*	Route de system de fichier attacher 
*************************************************************/
Route::post('attache', 'AttachmentController@attache')->name('attache_file');
Route::get('attache', 'AttachmentController@allatache')->name('attache_file_liste');
Route::post('detache', 'AttachmentController@detache')->name('detache_file');
/*************************************************************/



/*************************************************************
*	Route de du proile utilisateur  
*************************************************************/
Route::post('/profil/update', 'ProfilController@update')->name('profil_update');
Route::get('/register/confirm/{id}/{confirmation_token}', '\App\Http\Controllers\Auth\RegisterController@confirm')->name('register_confirm');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Auth::routes();
/*************************************************************/



/*************************************************************
*	Route des catégorie
*************************************************************/
Route::resource('categorie', 'CotegorieController',[
    'only' => ['index', 'show', 'store', 'update', 'destroy'],
]);
/*************************************************************/



/*************************************************************
*	Route des serveur
*************************************************************/
Route::resource('serveur', 'ServeurController',[
    'only' => ['index', 'show', 'store', 'update', 'destroy'],
]);
//@TODO: a suprimé
Route::get('/pln/install/{id}', 'ServeurController@installPLN')->name('installPLN');

Route::get('/serveur/whm/{id}', 'ServeurController@serveurWHM')->name('srv') ;

Route::post('/serveur/add/ips', 'ServeurController@add_ips')->name('srv') ;
/*************************************************************/



/*************************************************************
*	Route des site et de gestion de compte Cpanel 
*************************************************************/
Route::post('/site', 'SiteController@store')->name('site_store');
Route::post('/site/internetbs', 'SiteController@internetbs')->name('site_store');
Route::post('/site/runscript', 'SiteController@runscript')->name('site_store');
Route::get('/site', 'SiteController@index')->name('site_index');
Route::get('/site/{id}', 'SiteController@show')->name('site_index');
/*************************************************************/



/*************************************************************
*	Route de Gestion de wordpress  
*************************************************************/ 
Route::post('/wp/install', 'WPController@wp_install')->name('wp_widget');
Route::post('/log/create', 'SiteController@log_create')->name('log_create');
Route::post('/wp/theme', 'WPController@wp_theme')->name('wp_theme');
Route::post('/wp/plugin', 'WPController@wp_plugin')->name('wp_plugin');
Route::post('/wp/post', 'WPController@wp_post')->name('wp_post');
Route::post('/wp/widget', 'WPController@wp_widget')->name('wp_widget');
Route::get('/wp/session/{username}/{serveur_id}', 'WPController@wp_session')->name('wp_post');
Route::get('/history', 'SiteController@history')->name('history');
/*************************************************************/
