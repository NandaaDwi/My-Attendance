  <?php

  use Illuminate\Support\Facades\Route;
  use App\Http\Controllers\AuthController;
  use App\Http\Controllers\admin\DashboardController;
  use App\Http\Controllers\admin\UserController;
  use App\Http\Controllers\admin\AcademicYearController;
  use \App\Http\Controllers\admin\MajorController;
  use App\Http\Controllers\admin\StudentClassController;
  use App\Http\Controllers\admin\AttendanceController;
  use App\Http\Controllers\admin\AttendanceRecapController;
  use App\Http\Controllers\admin\AttendanceHistoryController;
  use App\Http\Controllers\admin\SettingsController;
  
  use App\Http\Controllers\staff\DashboardStaffController;
  use App\Http\Controllers\staff\AttendanceStaffController;
  use App\Http\Controllers\staff\AttendanceRecapStaffController;
  use App\Http\Controllers\staff\AcademyYearsStaffController;
  use App\Http\Controllers\staff\MajorStaffController;
  use App\Http\Controllers\staff\StudentClassStaffController;

  use App\Http\Controllers\student\DashboardStudentController;

  use App\Http\Controllers\parent\DashboardParentController;

  Route::get('/', [AuthController::class, 'loginForm'])->name('login');

  Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);

  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::prefix('admin')->as('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('admin/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('academic_year',  AcademicYearController::class)->except(['show', 'edit', 'create, destroy']);
    Route::resource('major', MajorController::class)->except(['create', 'show', 'edit', 'seacrh']);
    Route::resource('student-class', StudentClassController::class)->except(['create', 'edit']);
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance-recap', [AttendanceRecapController::class, 'index'])->name('attendance-recap.index');
    Route::post('/attendance-recap', [AttendanceRecapController::class, 'update'])->name('attendance-recap.update');
    Route::get('/attendance-history', [AttendanceHistoryController::class, 'index'])->name('attendance-history.index');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('profile', [SettingsController::class, 'profile'])->name('profile');
        Route::get('general', [SettingsController::class, 'general'])->name('general');
        Route::get('help', [SettingsController::class, 'help'])->name('help');
        Route::post('change-theme', [SettingsController::class, 'changeTheme'])->name('changeTheme');
    });
  });

  Route::prefix('staff')->as('staff.')->middleware(['auth', 'staff'])->group(function () {
    Route::get('dashboard', [DashboardStaffController::class, 'index'])->name('dashboard');
    Route::get('academic_year', [AcademyYearsStaffController::class, 'index'])->name('academic_year.index');
    Route::get('/major', [MajorStaffController::class, 'index'])->name('major.index');
    Route::get('/student-class', [StudentClassStaffController::class, 'index'])->name('student_class.index');
    Route::get('/attendance', [AttendanceStaffController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceStaffController::class, 'store'])->name('attendance.store');
    Route::get('/attendance-recap', [AttendanceRecapStaffController::class, 'index'])->name('attendance-recap.index');
    Route::post('/attendance-recap', [AttendanceRecapController::class, 'update'])->name('attendance-recap.update');
  });

  Route::prefix('student')->as('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [DashboardStudentController::class, 'index'])->name('dashboard');
    Route::get('/api/attendances', [DashboardStudentController::class, 'getDetailedAttendances'])->name('api.attendances');
  });

  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

  Route::prefix('parent')->as('parent.')->middleware(['auth', 'parent_student'])->group(function () {
    Route::get('/dashboard', [DashboardParentController::class, 'index'])->name('dashboard');
    Route::get('/api/attendances', [DashboardParentController::class, 'getDetailedAttendances'])->name('api.attendances');
  });

  