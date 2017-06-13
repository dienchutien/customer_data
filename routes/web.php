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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('ajax','AjaxController@SetProcess');

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'HomeController@index');
    
    // Route Project
    Route::get('list_users', 'UserController@getAllUser');
    Route::get('user/addedit', 'UserController@EditUser');
    Route::post('user/addedit','UserController@EditUser');

    // Route Project
    Route::get('list_projects', 'ProjectsController@getAllProject');
    Route::get('projects/addedit', 'ProjectsController@ListProjects');
    Route::post('projects/addedit','ProjectsController@ListProjects');
    
    // Route Project
    Route::get('list_supplier', 'SupplierController@getAllSupplier');
    Route::get('supplier/addedit', 'SupplierController@addEditSupplier');
    Route::post('supplier/addedit','SupplierController@addEditSupplier');

    // Route Channel
    Route::get('list_channel', 'ChannelController@getAllChannel');
    Route::get('channel/addedit', 'ChannelController@addEditChannel');
    Route::post('channel/addedit','ChannelController@addEditChannel');
    
    //Jobs
    Route::get('list_job', 'JobController@getAllJob');
    Route::get('job/addedit', 'JobController@addEditJob');
    Route::post('job/addedit','JobController@addEditJob');
    Route::get('export_excel_Jobs', 'JobController@exportJob');
    Route::get('job_statistics', 'JobController@jobStatistics');
    Route::get('export_Jobs_Statistics', 'JobController@exportJobStatistics');
//    Route::get('update_parent_channel', 'JobController@update_parent_channel');
        
    // Route Branch
    Route::get('list_branch', 'BranchController@getAllBranch');
    Route::get('branch/addedit', 'BranchController@addEditBranch');
    Route::post('branch/addedit','BranchController@addEditBranch');
    
    //// Route Role
    Route::get('list_role_group', 'RoleController@ListRoleGroup');
    Route::get('role/addedit', 'RoleController@editRoleGroup');
    Route::post('role/addedit','RoleController@editRoleGroup');
    Route::get('role/insert', 'RoleController@insertRoleGroup');
    Route::post('role/insert', 'RoleController@insertRoleGroup');
    //Customer
    Route::get('customer/list', 'DataController@getAllData');
    Route::get('customer/addedit', 'DataController@importExcel');
    Route::post('customer/addedit','DataController@importExcel');
    Route::get('data_transfer', 'DataController@DataTransfer');
    Route::get('customer/statistics', 'JobController@jobStatistics');
    Route::get('export_customer_statistics', 'JobController@exportJobStatistics');
});
