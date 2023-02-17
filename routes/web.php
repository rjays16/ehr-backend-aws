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
use App\Api\V1\Controllers\Auth\LogoutController;
use App\Api\V1\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AuthGeneratorController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



Route::group(['middleware' => ['web.report']], function () {
    Route::get('/doctor/patient/diagnostic/rad/report/pdf', function () {
        $service = new App\API\V1\Controllers\Diagnostic\DiagnosticController();
        return $service->getRadReportPdf(request());
    });

    Route::get('/doctor/patient/diagnostic/lab/report/pdf', function () {
        $service = new App\API\V1\Controllers\Diagnostic\DiagnosticController();
        return $service->getLabReportPdf(request());
    });


    Route::get('/printCf4', function () {
        $service = new App\API\V1\Controllers\ReportsController();
        return $service->generateCf4(request());
    });


    Route::get('/doctor/patient/prescription', function () {
        $service = new App\API\V1\Controllers\ReportsController();
        return $service->prescription(request());
    });
});


Route::get('/mobile/manual', function () {
    $service = new App\API\V1\Controllers\ReportsController();
    return $service->mobileManual(request());
});


Route::get('/logs', function () {
    $service = new Controller;
    return $service->logs(request());
});

Route::get('/', function () {
    $user = session()->get('user');
    if(!is_null($user)){
        return redirect('/landingPage');
    }
    return view('welcome');
});

Route::post('/loginAuth', function (Request $request) {
    $service = new LoginController;
    return $service->loginAuth($request);
});
Route::post('/logout', function () {
    $service = new LogoutController;
    return $service->logout_ihomp();
});

Route::group(['middleware' => 'web.auth'], function () {

    Route::post('/verifyEmail', function (Request $request) {
        $service = new AuthGeneratorController();
        return $service->verifyEmail($request);
    });

    Route::post('ajaxdata/getdata', function (Request $request) {
        $service = new AuthGeneratorController;
        return $service->getAuth($request);
    });

    Route::get('/landingPage', 'Auth\AuthGeneratorController@index');

});
