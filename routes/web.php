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
use App\Http\Middleware\Student;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'home'], function () {
    Route::any('student/index', [
     'uses' => 'StudentController@index',
     'as'   => 'studentindex'
   ]);
    Route::any('student/create', 'StudentController@create');
    Route::any('student/add', 'StudentController@add');
   //Route::any('student/update/{id}', 'StudentController@update')->middleware('student');
   Route::any('student/update/{id}', 'StudentController@update')->middleware(Student::class);
    Route::any('User/docorn', 'UserController@sendEmailReminder');
    Route::any('User/queue', 'UserController@emailqueue');
    Route::any('User/event', 'UserController@smsevent');
    Route::any('User/setcache', 'UserController@cacheredis');
    Route::any('User/getcache', 'UserController@getcacheredis');
    Route::any('User/upload', 'UserController@qiniuupload')->name('upload');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
