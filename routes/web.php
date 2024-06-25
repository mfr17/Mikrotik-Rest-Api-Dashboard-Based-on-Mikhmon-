<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\TrafficController;
use App\Http\Controllers\Api\RealtimeDataController;

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

Route::get('/rt', function () {
    return view('rt');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/realtime-data', function () {
            $routerInfo = session('active_router');

            $controller = new RealtimeDataController();
            return $controller->getRealtimeData(request());
        })->middleware('web');
        Route::get('/traffic/{interface}', function (Request $request, $interface) {
            $controller = new TrafficController();
            return $controller->monitorTraffic($request, $interface);
        })->middleware('web');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::controller(RouterController::class)->group(function () {
            Route::get('router', 'index')->name('router');
            Route::post('router', 'store')->name('router.store');
            Route::get('router/create', 'create')->name('router.create');
            Route::post('router/use/{index}', 'useRouter')->name('router.use');
            Route::get('router/edit/{index}', 'edit')->name('router.edit');
            Route::put('router/update/{index}', 'update')->name('router.update');
            Route::delete('router/delete/{index}', 'delete')->name('router.delete');
        });
        Route::controller(HotspotController::class)->group(function () {
            Route::get('hotspot/user', 'index')->name('hotspot.user');
            Route::get(
                'hotspot/user/create',
                'create'
            )->name('hotspot.user.create');
            Route::post('hotspot/user', 'store')->name('hotspot.user.store');
        });
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// useless routes
// Just to demo sidebar dropdown links active states.
Route::get('/buttons/text', function () {
    return view('buttons-showcase.text');
})->middleware(['auth'])->name('buttons.text');

Route::get('/buttons/icon', function () {
    return view('buttons-showcase.icon');
})->middleware(['auth'])->name('buttons.icon');

Route::get('/buttons/text-icon', function () {
    return view('buttons-showcase.text-icon');
})->middleware(['auth'])->name('buttons.text-icon');

require __DIR__ . '/auth.php';
