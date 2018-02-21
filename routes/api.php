<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'admin'], function (){

   /*
   |--------------------------------------------------------------------------
   | Categories
   |--------------------------------------------------------------------------
   */
    Route::resource('categories', 'Forms\CategoryAPIController');

   /*
   |--------------------------------------------------------------------------
   | Forms
   |--------------------------------------------------------------------------
   */
    Route::resource('forms', 'Forms\FormAPIController');

    /*
    |--------------------------------------------------------------------------
    | Form steps
    |--------------------------------------------------------------------------
    */
    Route::post('steps/{form}', 'Forms\FormStepAPIController@store');
    Route::put('steps/{step}', 'Forms\FormStepAPIController@update');
    Route::delete('steps/{step}', 'Forms\FormStepAPIController@destroy');

    /*
    |--------------------------------------------------------------------------
    | Form questions
    |--------------------------------------------------------------------------
    */
    Route::post('questions/{step}', 'Forms\FormQuestionAPIController@store');
    Route::put('questions/{question}', 'Forms\FormQuestionAPIController@update');
    Route::delete('questions/{question}', 'Forms\FormQuestionAPIController@destroy');

    /*
    |--------------------------------------------------------------------------
    | Form answers
    |--------------------------------------------------------------------------
    */
    Route::post('answers/{question}', 'Forms\FormQuestionAnswerAPIController@store');
    Route::put('answers/{answer}', 'Forms\FormQuestionAnswerAPIController@update');
    Route::delete('answers/{answer}', 'Forms\FormQuestionAnswerAPIController@destroy');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'Settings\ProfileController@user');
    Route::post('logout', 'Auth\LoginController@logout');
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => ['guest:api', 'web']], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

/*
 |--------------------------------------------------------------------------
 | Forms
 |--------------------------------------------------------------------------
*/
Route::resource('forms', 'Forms\FormAPIController', ['only' => ['index', 'show']]);

/*
 |--------------------------------------------------------------------------
 | Categories
 |--------------------------------------------------------------------------
*/
Route::resource('categories', 'Forms\CategoryAPIController', ['only' => ['index', 'show']]);

