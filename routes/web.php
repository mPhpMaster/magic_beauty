<?php

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

Route::get('/test', function () {
    $url = "https://demo3.odoo.com";
    $db = "demo_140_1625734321";
    $username = "admin";
    $password = "admin";
//    require_once('ripcord.php');
//    $info = ripcord::client('https://demo.odoo.com/start')->start();
//    list($url, $db, $username, $password) =
//        array($info['host'], $info['database'], $info['user'], $info['password']);

    if(!($uid = session('uid'))) {
        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());
        session([
            'uid' => $uid,
        ]);
        session()->save();
    }

    $models = ripcord::client("$url/xmlrpc/2/object");
//    $result = $models->execute_kw($db, $uid, $password,
//        'res.partner', 'check_access_rights',
//        array('read'), array('raise_exception' => false));

//    $results = $models->execute_kw($db, $uid, $password,
//        'res.partner', 'search', array(
//            array(array('is_company', '=', true),
//                array('customer', '=', true))));
    $ids = $models->execute_kw($db, $uid, $password,
        'res.partner', 'search',
        array(array(array('is_company', '=', true),
            /*array('customer', '=', true)*/)),
        array('limit'=>1));
    $results = $models->execute_kw($db, $uid, $password,
        'res.partner', 'read', array($ids));

    dd(compact('results','url','db','username','password'),$uid);
    return view('privacy_policy');
});
Route::get('/', function () {
    return view('privacy_policy');
});
Route::get('/privacy_policy', function () {
    return view('privacy_policy');
})->name('privacy_policy');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
