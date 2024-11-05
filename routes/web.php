<?php

use App\Livewire\Admin\AttendanceMain;
use App\Livewire\Admin\GroupMain;
use App\Livewire\Admin\MemberMain;
use App\Livewire\Dashboard\Main;
use App\Livewire\Web\About;
use App\Livewire\Web\Blog;
use App\Livewire\Web\Contact;
use App\Livewire\Web\Team;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/about',About::class)->name('about');
Route::get('/team',Team::class)->name('team');
Route::get('/blog',Blog::class)->name('blog');
Route::get('/contact',Contact::class)->name('contact');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',Main::class)->name('dashboard');
    Route::get('/attendance',AttendanceMain::class)->name('attendance');
    Route::get('/members',MemberMain::class)->name('members');
    Route::get('/groups',GroupMain::class)->name('groups');

    // Route::get('/export/attendances/pdf', [AttendanceMain::class, 'exportToPdf'])->name('attendances.export.pdf');
    // Route::get('/export/attendances/pdf/{group_id?}', [AttendanceMain::class, 'exportToPdf'])->name('attendances.export.pdf');
    Route::get('/export/attendances/pdf/{group_id?}/{year?}/{month?}/{day?}', [AttendanceMain::class, 'exportToPdf'])
    ->name('attendances.export.pdf');
});
