<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function (\App\Repositories\FormRepository $repository) {

   
    $data = json_decode('{
    "velit-officia":["test", "test1"],
     "facere-inventore":
     [{"commodi-quaerat":["animi1","quis1"],"odit-et":"test1"},
     {"odit-et":"test","commodi-quaerat":["fugiat"],"repudiandae-similique":"molestias"},
     {"odit-et":"test","commodi-quaerat":["itaque"],"repudiandae-similique":"architecto"}],
     "aut-voluptates":[{"quo-quas":"aut"}],
     "veniam-voluptatem":"eius",
     "1":"test",
     "3":"test",
     "2":"test",
     "dolor-voluptates":[{"velit-corporis":["quo","necessitatibus"]}],"ut-repudiandae":[{"in-in":"nemo"}]}', true);

   return $repository->generate($repository->find(72), $data);

});

Route::prefix('admin-panel')->middleware(['admin'])->group(function () {
    Route::get('media/{media}', 'API\Forms\FormAPIController@template');
    Route::get('forms/{id}/import', 'API\Forms\FormAPIController@import');
});

Route::post('forms/{id}/result', 'API\Forms\FormAPIController@result');
Route::post('forms/{id}/save/{edit?}', 'API\Forms\FormAPIController@save');

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');

Route::get('password/reset/{token}', function () {
    return view('index');
})->name('password.reset');
