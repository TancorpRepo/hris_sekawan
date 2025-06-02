<?php

use App\Filament\Pages\AbsenPage;
use App\Filament\Pages\AttendancePage;
use App\Models\Employee;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.welcome');
    // return redirect()->route('filament.admin.pages.attendance-page');
});

Route::get('/search-personnel-no', [AbsenPage::class, 'searchPersonnelNo'])->name('search-personnel-no');

// Route::get('/get-timezone', function (Illuminate\Http\Request $request) {
//     $latitude = $request->query('latitude');
//     $longitude = $request->query('longitude');

//     if ($latitude && $longitude) {
//         try {
//             $timezone = CarbonTimeZone::createFromCoordinates($latitude, $longitude);
//             return response()->json(['timezone' => $timezone->getName()]);
//         } catch (\Exception $e) {
//             return response()->json(['error' => 'Unable to determine timezone'], 400);
//         }
//     }

//     return response()->json(['error' => 'Invalid coordinates'], 400);
// });

// Route::get('/attendance-page', function () {
//     return redirect()->route('filament.admin.pages.attendance-page');
// });
