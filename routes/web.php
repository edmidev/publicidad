<?php

use Illuminate\Support\Facades\Route;
use App\Models\TherapeuticBehavior;
use App\Models\TherapyDetail;

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
    return redirect('/login');
});

// Route::get('/fix/database', function() {
//     $behaviors = TherapeuticBehavior::all();
//     foreach ($behaviors as $b) {
//         $details = TherapyDetail::where('therapeutic_behavior_id', $b->id)->orderBy('date', 'asc')->get();
//         foreach ($details as $key => $d) {
//             $d->session_number = $key + 1;
//             $d->save();
//         }
//     }
//     return 'Fixed';
// });


Route::get('/{vue_capture?}', function () {
    return view('index');
})->where('vue_capture', '^(?!api).*$');


