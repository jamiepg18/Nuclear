<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function ()
{
    return view('welcome');
});

/**
 * Reactor routes
 */
Route::group(['prefix' => 'reactor'], function ()
{
    // Dashboard
    Route::get('/', ['uses' => 'DashboardController@index',
                     'as' => 'reactor.dashboard']);

    // Authentication
    Route::get('auth/login', ['uses' => 'Auth\AuthController@getLogin',
        'as' => 'reactor.auth.login']);
    Route::post('auth/login', ['uses' => 'Auth\AuthController@postLogin',
        'as' => 'reactor.auth.login.post']);
    Route::get('auth/logout', ['uses' => 'Auth\AuthController@getLogout',
        'as' => 'reactor.auth.logout']);

    // Password Reset
    Route::get('password/email', ['uses' => 'Auth\PasswordController@getEmail',
        'as' => 'reactor.password.email']);
    Route::post('password/email', ['uses' => 'Auth\PasswordController@postEmail',
        'as' => 'reactor.password.email.post']);

    Route::get('password/reset/{token}', ['uses' => 'Auth\PasswordController@getReset',
        'as' => 'reactor.password.reset']);
    Route::post('password/reset', ['uses' => 'Auth\PasswordController@postReset',
        'as' => 'reactor.password.reset.post']);

    // Users
    Route::resource('users', 'UsersController', ['except' => 'show', 'names' => [
        'index' => 'reactor.users.index',
        'create' => 'reactor.users.create',
        'store' => 'reactor.users.store',
        'edit' => 'reactor.users.edit',
        'update' => 'reactor.users.update',
        'destroy' => 'reactor.users.destroy',
    ]]);
    Route::get('users/search', ['uses' => 'UsersController@search',
        'as' => 'reactor.users.search']);
    Route::get('users/{id}/password', ['uses' => 'UsersController@password',
        'as' => 'reactor.users.password']);
    Route::put('users/{id}/password', ['uses' => 'UsersController@updatePassword',
        'as' => 'reactor.users.password.post']);
    Route::get('users/{id}/permissions', ['uses' => 'UsersController@permissions',
        'as' => 'reactor.users.permissions']);
    Route::put('users/{id}/permissions', ['uses' => 'UsersController@addPermission',
        'as' => 'reactor.users.permission.add']);
    Route::delete('users/{id}/permissions', ['uses' => 'UsersController@removePermission',
        'as' => 'reactor.users.permission.remove']);
    Route::get('users/{id}/roles', ['uses' => 'UsersController@roles',
        'as' => 'reactor.users.roles']);
    Route::put('users/{id}/roles', ['uses' => 'UsersController@addRole',
        'as' => 'reactor.users.role.add']);
    Route::delete('users/{id}/roles', ['uses' => 'UsersController@removeRole',
        'as' => 'reactor.users.role.remove']);
    Route::get('users/{id}/history', ['uses' => 'UsersController@history',
        'as' => 'reactor.users.history']);

    // Roles
    Route::resource('roles', 'RolesController', ['except' => 'show', 'names' => [
        'index' => 'reactor.roles.index',
        'create' => 'reactor.roles.create',
        'store' => 'reactor.roles.store',
        'edit' => 'reactor.roles.edit',
        'update' => 'reactor.roles.update',
        'destroy' => 'reactor.roles.destroy',
    ]]);
    Route::get('roles/search', ['uses' => 'RolesController@search',
        'as' => 'reactor.roles.search']);
    Route::get('roles/{id}/permissions', ['uses' => 'RolesController@permissions',
        'as' => 'reactor.roles.permissions']);
    Route::put('roles/{id}/permissions', ['uses' => 'RolesController@addPermission',
        'as' => 'reactor.roles.permission.add']);
    Route::delete('roles/{id}/permissions', ['uses' => 'RolesController@removePermission',
        'as' => 'reactor.roles.permission.remove']);
    Route::get('roles/{id}/users', ['uses' => 'RolesController@users',
        'as' => 'reactor.roles.users']);
    Route::put('roles/{id}/users', ['uses' => 'RolesController@addUser',
        'as' => 'reactor.roles.user.add']);
    Route::delete('roles/{id}/users', ['uses' => 'RolesController@removeUser',
        'as' => 'reactor.roles.user.remove']);

    // Permissions
    Route::resource('permissions', 'PermissionsController', ['except' => 'show', 'names' => [
        'index' => 'reactor.permissions.index',
        'create' => 'reactor.permissions.create',
        'store' => 'reactor.permissions.store',
        'edit' => 'reactor.permissions.edit',
        'update' => 'reactor.permissions.update',
        'destroy' => 'reactor.permissions.destroy',
    ]]);
    Route::get('permissions/search', ['uses' => 'PermissionsController@search',
        'as' => 'reactor.permissions.search']);

});
