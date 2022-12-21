<?php

use App\Models\Job;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
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


/*
|   Jobs area
|
*/
// jobs list
Route::get('/', [JobController::class, 'index'])->name('home');

// show create form
Route::get('/jobs/create', [JobController::class, 'create'])->middleware('auth');

// Store Job data
Route::post('/jobs', [JobController::class, 'store'])->middleware('auth');

// Job edit form
Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middleware('auth');

// Edit Job Action (Update)
Route::put('/jobs/{job}', [JobController::class, 'update'])->middleware('auth');

// Delete Job 
Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->middleware('auth');

// manage jobs
Route::get('/jobs/manage', [JobController::class, 'manage'])->middleware('auth');

// single job
Route::get('/jobs/{job}', [JobController::class, 'show'])->where('id', '[0-9]+');




/*
|   Users Area
|
*/

// Show Register form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Create a new user
Route::post('/users', [UserController::class, 'store'])->middleware('guest');

// Logout users
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// login user
Route::post('/users/login', [UserController::class, 'authenticate'])->middleware('guest');



/* 
    Alternative ways for reference
*/

// jobs list without a custom controller
Route::get('/jobs-no-controller', function () {
    return view('jobs', [
        "jobs" => Job::all()
    ]);
});

// single job without custom controller
Route::get("/jobs-no-controller/{job}", function (Job $job){
    return view('job', [
        'job' => $job
    ]);
});

// Single post without binding
Route::get("/jobs-no/{id}", function ($id){
    $job = Job::find($id);
    if ($job) {
        return view('job', [
            'job' => $job
        ]);
    } else {
        abort('404');
    }
});



Route::get('/hello', function () {
    return response('<h1>Hello world!</h1>');
});

Route::get("/post/{id}", function ($id){
    return response("Post" . $id);
})->where('id', '[0-9]+');