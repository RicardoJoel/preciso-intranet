<?php

use Illuminate\Support\Facades\Route;
use App\ExchangeRate;
use App\DocumentType;
use App\Country;
use App\Proposal;
use App\Project;
use App\Visit;
use App\User;

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

Auth::routes(['verify' => true]);

Route::group([
    'middleware' => 'guest',
], function () {
    Route::get('/', function () { return view('auth.login'); });
    Route::get('verify', function () { return view('auth.verify'); });  
    Route::get('activate/{code}', 'UserController@activate');
    Route::post('complete/{id}', 'UserController@complete');  
});

Route::group([
    'middleware' => 'verified',
], function () {
    Route::get('profile', function () { return view('auth.profile'); })->name('profile');
    Route::get('password', function () { return view('auth.passwords.update'); })->name('password');
    Route::post('updateAccount', 'UserController@updateAccount')->name('updateAccount');
    Route::post('changePassword', 'Auth\ChangePasswordController@store')->name('changePassword');
    Route::resource('activities', 'ActivityController', ['except' => ['show','update','destroy']]);
    Route::post('activities.update', 'ActivityController@update')->name('activities.update');
    Route::get('activities.destroy/{id}', 'ActivityController@destroy');
    Route::get('tasks.getByParent/{id}', 'TaskController@getByParent');
    Route::get('getProject/{id}', 'ProjectController@get');
});

Route::group([
    'middleware' => 'isnt_admin',
], function () {
    Route::get('home', 'HomeController@home')->name('home');
});

Route::group([
    'middleware' => 'is_admin',
    'prefix' => 'admin'
], function () {
    Route::resource('users', 'UserController');
    Route::resource('visits', 'VisitController');
    Route::resource('projects', 'ProjectController');
    Route::resource('customers', 'CustomerController');
    Route::resource('suppliers', 'SupplierController');
    Route::resource('freelancers', 'FreelancerController');
    Route::resource('dependents', 'DependentController');
    Route::resource('parameters', 'ParameterController');
    Route::resource('variations', 'VariationController');
    Route::resource('proposals', 'ProposalController');
    Route::resource('contacts', 'ContactController');
    Route::resource('employees', 'EmployeeController');
    Route::resource('independents', 'IndependentController');
    Route::resource('providers', 'ProviderController');
    Route::resource('sellers', 'SellerController');
    Route::resource('resources', 'ResourceController');
    Route::resource('tasks', 'TaskController');

    Route::get('profilesByType/{type}', function ($type) { return Profile::where('type',$type)->get(); });    
    Route::get('documentType/{id}', function ($id) { return DocumentType::find($id); });
    Route::get('country/{id}', function ($id) { return Country::find($id); });
    Route::get('user/{id}', function ($id) { return User::find($id); });
    Route::get('currentRate', function () { return ExchangeRate::where('date',ExchangeRate::max('date'))->get()->first(); });
    Route::get('getNextCode/{code}', function ($code) {
        if (str_contains($code,'_')) return '___';
        $maxVst = Visit::where('prop_code','like',$code.'%')->max(\DB::raw('substr(prop_code,13,3)'));
        $maxPrp = Proposal::where('code','like',$code.'%')->max(\DB::raw('substr(code,13,3)'));
        $maxPrj = Project::where('code','like',$code.'%')->max(\DB::raw('substr(code,13,3)'));
        $maxCod = max($maxVst,$maxPrp,$maxPrj);
        return str_pad(++$maxCod,3,'0',STR_PAD_LEFT);
    });

    Route::get('customers.getByDocument/{doc}', 'CustomerController@getByDocument')->name('customers.getByDocument');
    Route::get('customers.searchByFilter', 'CustomerController@searchByFilter')->name('customers.searchByFilter');
    
    Route::get('users.getByDocument/{doc}', 'UserController@getByDocument')->name('users.getByDocument');
    Route::get('users.searchByFilter', 'UserController@searchByFilter')->name('users.searchByFilter');

    Route::get('visits.searchByFilter', 'VisitController@searchByFilter')->name('visits.searchByFilter');
    
    Route::post('proposals.storeVersion/{id}', 'ProposalController@storeVersion')->name('proposals.storeVersion');
    Route::post('proposals.updateVersion/{id}', 'ProposalController@updateVersion')->name('proposals.updateVersion');
    Route::post('proposals.approve/{id}', 'ProposalController@approve')->name('proposals.approve');
    Route::post('proposals.reject/{id}', 'ProposalController@reject')->name('proposals.reject');
    Route::get('proposals/{id}/createVersion', 'ProposalController@createVersion')->name('proposals.createVersion');
    Route::get('proposals/{id}/editVersion', 'ProposalController@editVersion')->name('proposals.editVersion');
    Route::get('proposals.searchByFilter', 'ProposalController@searchByFilter')->name('proposals.searchByFilter');

    Route::get('activities.getByProjUser', 'ActivityController@getByProjUser')->name('activities.getByProjUser');
    Route::get('activities.generate', 'ActivityController@generate')->name('activities.generate');
    Route::get('activities.download', 'ActivityController@download')->name('activities.download');
    Route::get('activities.projects', 'ActivityController@projects')->name('activities.projects');
    Route::get('activities.report', 'ActivityController@report')->name('activities.report');
    Route::get('activities.status', 'ActivityController@status')->name('activities.status');
    Route::get('activities.users', 'ActivityController@users')->name('activities.users');
    
    Route::post('tasks.moveUp/{id}', 'TaskController@moveUp')->name('tasks.moveUp');
    Route::post('tasks.moveDown/{id}', 'TaskController@moveDown')->name('tasks.moveDown');

    Route::get('home', 'HomeController@adminHome')->name('home');
});