<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    // This takes the flash data from the controller and pushes it forward to the next redirect
    session()->reflash();
    return match (Auth::user()->role) {
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default   => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    | Profile
    */
    Route::get('/profile',function(){
        // This takes the flash data from the controller and pushes it forward to the next redirect
        session()->reflash();
        return match (Auth::user()->role){
            'teacher' => redirect()->route('teacher.profile'),
            'student' => redirect()->route('student.profile'),
        };
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('allowed:teacher')->prefix('dashboard/teacher')->name('teacher.')->group(function () {

        Route::get('/', [TeacherController::class, 'index'])
            ->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::get('/assignments/create', [TeacherController::class, 'new'])
            ->name('assignments.create');

        Route::post('/assignments', [TeacherController::class, 'newAssignment'])
            ->name('assignments.store');

        Route::get('/assignments/{assignment}/edit', [TeacherController::class, 'edit'])
            ->name('assignments.edit');

        Route::put('/assignments/{assignment}', [TeacherController::class, 'updateAssignment'])
            ->name('assignments.update');

        Route::delete('/assignments/{assignment}', [TeacherController::class, 'dropAssignment'])
            ->name('assignments.destroy');
        Route::get('/submissions/{id}',[TeacherController::class,'reviewSubmission'])
            ->name('submissions.review');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('allowed:student')->prefix('dashboard/student')->name('student.')->group(function () {

        Route::get('/', [StudentController::class, 'index'])
            ->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::get('/assignments/{assignment}', [StudentController::class, 'start'])
            ->name('assignments.show');

        Route::post('/assignments/{assignment}/submit', [StudentController::class, 'saveSubmission'])
            ->name('assignments.submit');
    });
});

require __DIR__.'/auth.php';
