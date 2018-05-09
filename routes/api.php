<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* CORS */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('signup', function (Request $request) {
    return App\User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        'api_token' => str_random(60)
    ]);
});

Route::post('login', function (Request $request) {
    $authenticated = auth()->attempt([
        'email' => $request->input('email'),
        'password' => $request->input('password')
    ]);

    if ($authenticated) {
        $user = auth()->user();
        $user->api_token = str_random(60);
        $user->save();
        return $user;
    }

    return response()->json([
        'error' => 'Unauthenticated user',
        'code' => 401
    ], 401);
});

Route::middleware('auth:api')->post('logout', function (Request $request) {
    $user = auth()->user();
    
    if ($user) {
        $user->api_token = null;
        $user->save();
        
        return response()->json([
            'message' => 'Thank you for using our application',
        ]);
    }

    return response()->json([
        'error' => 'Unable to logout user',
        'code' => 401
    ]);
});

Route::apiResource('task', 'TaskController');