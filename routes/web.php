<?php

use App\Http\Controllers\AtsReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\MessageRoomController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\UserInformationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Auth::routes(['verify'=>true]);

/** Admin Route Start */
Route::middleware(['auth', 'verified', 'user-access:admin'])->group(function () {
    Route::prefix('/admin')->group(function (){
        Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');

        // admin/school routes
        Route::prefix('/school')->group(function (){
            Route::get('/add', [SchoolController::class, 'create'])->name('admin.school.add');
            Route::post('/add', [SchoolController::class, 'store'])->name('admin.school.store');
            Route::get('/list', [SchoolController::class, 'index'])->name('admin.school.list');
            Route::get('/view/{school}', [SchoolController::class, 'show'])->name('admin.school.view');
            Route::get('/edit/{school}', [SchoolController::class, 'edit'])->name('admin.school.edit');
            Route::post('/update/{school}', [SchoolController::class, 'update'])->name('admin.school.update');
            Route::get('/delete/{school}', [SchoolController::class, 'destroy'])->name('admin.school.delete');
        });

        // admin/department routes
        Route::prefix('/department')->group(function (){
            Route::get('/add', [DepartmentController::class, 'create'])->name('admin.department.add');
            Route::post('/add', [DepartmentController::class, 'store'])->name('admin.department.store');
            Route::get('/list', [DepartmentController::class, 'index'])->name('admin.department.list');
            Route::get('/view/{department}', [DepartmentController::class, 'show'])->name('admin.department.view');
            Route::get('/edit/{department}', [DepartmentController::class, 'edit'])->name('admin.department.edit');
            Route::post('/update/{department}', [DepartmentController::class, 'update'])->name('admin.department.update');
            Route::get('/delete/{department}', [DepartmentController::class, 'destroy'])->name('admin.department.delete');
        });

        // admin/staff routes
        Route::prefix('/staff')->group(function (){
            Route::get('/add', [UserController::class, 'staffCreate'])->name('admin.staff.add');
            Route::post('/add', [UserController::class, 'staffStore'])->name('admin.staff.store');
            Route::get('/list', [UserController::class, 'staffIndex'])->name('admin.staff.list');
            Route::get('/view/{user}', [UserController::class, 'staffShow'])->name('admin.staff.view');
            Route::get('/edit/{user}', [UserController::class, 'staffEdit'])->name('admin.staff.edit');
            Route::post('/update/{user}', [UserController::class, 'staffUpdate'])->name('admin.staff.update');
            Route::get('/delete/{user}', [UserController::class, 'destroy'])->name('admin.staff.delete');
        });

        // admin/internship route
        Route::prefix('/internship')->group(function (){
            Route::get('/list', [InternshipController::class, 'index'])->name('admin.internship.list');
            Route::get('/view/{internship}', [InternshipController::class, 'show'])->name('admin.internship.view');
            Route::get('/start/{internship}', [InternshipController::class, 'start'])->name('admin.internship.start');
            Route::get('/delete/{internship}', [InternshipController::class, 'destroy'])->name('admin.internship.delete');
        });

        // admin/application route
        Route::prefix('/application')->group(function (){
            Route::get('/list', [UserApplicationController::class, 'index'])->name('admin.application.list');
            Route::get('/delete/{userapplication}', [UserApplicationController::class, 'destroy'])->name('admin.application.delete');
        });

        // admin/profile routes
        Route::prefix('/profile')->group(function (){
            Route::get('/', [UserController::class, 'profile'])->name('admin.profile');
            Route::post('/setting', [UserInformationController::class, 'store'])->name('admin.profile.setting');
            Route::post('/password/{user}', [UserController::class, 'passwordChange'])->name('admin.profile.password');
        });

        // admin/intern routes
        Route::prefix('/intern')->group(function (){
            Route::get('/list', [InternController::class, 'index'])->name('admin.intern.list');
            Route::get('/view/{intern}', [InternController::class, 'show'])->name('admin.intern.view');
        });

        // admin/reports route
        Route::prefix('/reports')->group(function (){
            Route::get('/application', [AtsReportController::class, 'adminApplication'])->name('admin.reports.application');
            Route::get('/internship', [AtsReportController::class, 'adminInternship'])->name('admin.reports.internship');
        });

    });
});
/** Admin Route End */

/** Department Route Start */
Route::middleware(['auth', 'verified', 'user-access:department'])->group(function () {
    Route::prefix('/department')->group(function (){
        Route::get('/home', [DashboardController::class, 'departmentIndex'])->name('department.home');

        // department/internship routes
        Route::prefix('/internship')->group(function (){
            Route::get('/add', [InternshipController::class, 'create'])->name('department.internship.add');
            Route::post('/add', [InternshipController::class, 'store'])->name('department.internship.store');
            Route::get('/list', [InternshipController::class, 'departmentIndex'])->name('department.internship.list');
            Route::get('/view/{internship}', [InternshipController::class, 'show'])->name('department.internship.view');
            Route::get('/edit/{internship}', [InternshipController::class, 'edit'])->name('department.internship.edit');
            Route::post('/update/{internship}', [InternshipController::class, 'update'])->name('department.internship.update');
            Route::post('/updatepre/{internship}', [InternshipController::class, 'updatePrerequisite'])->name('department.internship.updatePre');
            Route::get('/delete/{internship}', [InternshipController::class, 'destroy'])->name('department.internship.delete');
            Route::get('/start/{internship}', [InternshipController::class, 'start'])->name('department.internship.start');
        });

        // department/application route
        Route::prefix('/application')->group(function (){
            Route::get('/list', [UserApplicationController::class, 'departmentIndex'])->name('department.application.list');
            Route::get('/view/{userapplication}', [UserApplicationController::class, 'show'])->name('department.application.view');
            Route::get('/accept/{userapplication}', [UserApplicationController::class, 'acceptApplication'])->name('department.application.accept');
            Route::get('/reject/{userapplication}', [UserApplicationController::class, 'rejectApplication'])->name('department.application.reject');
            Route::get('/reset/{userapplication}', [UserApplicationController::class, 'resetApplication'])->name('department.application.reset');
            Route::get('/delete/{userapplication}', [UserApplicationController::class, 'destroy'])->name('department.application.delete');
            Route::get('/filter', [UserApplicationController::class, 'filter'])->name('department.application.filter');
        });

        // department/profile routes
        Route::prefix('/profile')->group(function (){
            Route::get('/', [UserController::class, 'profile'])->name('department.profile');
            Route::post('/setting', [UserInformationController::class, 'store'])->name('department.profile.setting');
            Route::post('/password/{user}', [UserController::class, 'passwordChange'])->name('department.profile.password');
        });

        // department/intern routes
        Route::prefix('/intern')->group(function (){
            Route::get('/list', [InternController::class, 'departmentIndex'])->name('department.intern.list');
            Route::get('/view/{intern}', [InternController::class, 'show'])->name('department.intern.view');
            Route::get('/delete/{intern}', [InternController::class, 'destroy'])->name('department.intern.delete');
        });

        // department/reports route
        Route::prefix('/reports')->group(function (){
            Route::get('/application', [AtsReportController::class, 'departmentApplication'])->name('department.reports.application');
            Route::get('/internship', [AtsReportController::class, 'departmentInternship'])->name('department.reports.internship');
        });

        // department/messages route
        Route::prefix('/messages')->group(function (){
            Route::get('/', [MessageRoomController::class, 'departmentIndex'])->name('department.message');
            Route::get('/{message_room}', [MessageRoomController::class, 'departmentShow'])->name('department.message.view');
            Route::post('/create', [MessageRoomController::class, 'store'])->name('department.message.store');
        });
    });
});
/** Department Route End */

/** School Route Start */
Route::middleware(['auth', 'verified', 'user-access:school'])->group(function () {
    Route::prefix('/school')->group(function (){
        Route::get('/home', [DashboardController::class, 'schoolIndex'])->name('school.home');

        // school/department routes
        Route::prefix('/department')->group(function (){
            Route::get('/add', [DepartmentController::class, 'create'])->name('school.department.add');
            Route::post('/add', [DepartmentController::class, 'store'])->name('school.department.store');
            Route::get('/list', [DepartmentController::class, 'schoolIndex'])->name('school.department.list');
            Route::get('/view/{department}', [DepartmentController::class, 'show'])->name('school.department.view');
            Route::get('/edit/{department}', [DepartmentController::class, 'edit'])->name('school.department.edit');
            Route::post('/update/{department}', [DepartmentController::class, 'update'])->name('school.department.update');
            Route::get('/delete/{department}', [DepartmentController::class, 'destroy'])->name('school.department.delete');
        });

        // school/internship route
        Route::prefix('/internship')->group(function (){
            Route::get('/list', [InternshipController::class, 'schoolIndex'])->name('school.internship.list');
            Route::get('/view/{internship}', [InternshipController::class, 'show'])->name('school.internship.view');
            Route::get('/start/{internship}', [InternshipController::class, 'start'])->name('school.internship.start');
            Route::get('/delete/{internship}', [InternshipController::class, 'destroy'])->name('school.internship.delete');
        });

        // school/application route
        Route::prefix('/application')->group(function (){
            Route::get('/list', [UserApplicationController::class, 'schoolIndex'])->name('school.application.list');
            Route::get('/delete/{userapplication}', [UserApplicationController::class, 'destroy'])->name('school.application.delete');
        });

        // school/profile routes
        Route::prefix('/profile')->group(function (){
            Route::get('/', [UserController::class, 'profile'])->name('school.profile');
            Route::post('/setting', [UserInformationController::class, 'store'])->name('school.profile.setting');
            Route::post('/password/{user}', [UserController::class, 'passwordChange'])->name('school.profile.password');
        });

        // school/intern routes
        Route::prefix('/intern')->group(function (){
            Route::get('/list', [InternController::class, 'schoolIndex'])->name('school.intern.list');
            Route::get('/view/{intern}', [InternController::class, 'show'])->name('school.intern.view');
        });

        // school/reports route
        Route::prefix('/reports')->group(function (){
            Route::get('/application', [AtsReportController::class, 'schoolApplication'])->name('school.reports.application');
            Route::get('/internship', [AtsReportController::class, 'schoolInternship'])->name('school.reports.internship');
        });
    });
});
/** School Route End */

/** User Route Start */
Route::middleware(['auth', 'verified', 'user-access:user'])->group(function () {
    Route::prefix('/user')->group(function (){
        Route::get('/home', [HomeController::class, 'usersHome'])->name('user.home');

        // user/internship routes
        Route::prefix('/internship')->group(function (){
            Route::get('/view/{internship}', [InternshipController::class, 'show'])->name('user.internship.view');
            Route::get('/apply/{internship}', [UserApplicationController::class, 'create'])->name('user.internship.apply');
            Route::post('/apply/{internship}', [UserApplicationController::class, 'store'])->name('user.internship.store');
        });
        // user/profile routes
        Route::prefix('/profile')->group(function (){
            Route::get('/', [UserController::class, 'profile'])->name('user.profile');
            Route::post('/setting', [UserInformationController::class, 'store'])->name('user.profile.setting');
            Route::post('/password/{user}', [UserController::class, 'passwordChange'])->name('user.profile.password');
            Route::post('/upload', [UserInformationController::class, 'upload'])->name('user.profile.upload');
        });

        // user/application routes
        Route::prefix('/application')->group(function (){
            Route::get('/list', [UserApplicationController::class, 'userIndex'])->name('user.application.list');
            Route::get('/delete/{userapplication}', [UserApplicationController::class, 'revoke'])->name('user.application.delete');
        });

        // user/messages route
        Route::prefix('/messages')->group(function (){
            Route::get('/', [MessageRoomController::class, 'userIndex'])->name('user.message');
            Route::get('/{message_room}', [MessageRoomController::class, 'userShow'])->name('user.message.view');
            Route::post('/create', [MessageRoomController::class, 'store'])->name('user.message.store');
        });
    });
});
/** User Route End */



Route::get('/start', function(){
    return view('email.start');
});
