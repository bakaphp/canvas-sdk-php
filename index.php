<?php

require_once 'vendor/autoload.php';
use Kanvas\Sdk\Kanvas;
use Kanvas\Sdk\Users;
use Kanvas\Sdk\Models\Users as UserModel;
use Kanvas\Sdk\Companies;
use Kanvas\Sdk\Auth;

Kanvas::setApiKey('asdeaefaefaefae');
$auth = Auth::auth(['email' => 'max@mctekk.com', 'password' => 'nosenose']);



/**
 * Create a new user.
 */
// $users = Users::create([
//     'firstname'=>'testSDK',
//     'lastname'=> 'testSDK',
//     'displayname'=> 'sdktester',
//     'password'=> 'nosenose',
//     'default_company'=> 'example sdk',
//     'email'=> 'examplesd5k@gmail.com',
//     'verify_password'=> 'nosenose'
//     ]);

/**
 * Update user info by its id.
 */
// $users = Users::update('84',[
//     'firstname'=>'testSDK',
//     'lastname'=> 'testSDK',
//     ]);

/**
 * Delete user by its id.
 */
// $users = Users::delete('99');

/**
 * Retrieve a user by its id.
 */
// $users = Users::retrieve('2', [], ['relationships'=>['roles']]);

/**
 * Retrieve all users.
 */
// $users = Users::all([], [
//     // 'relationships' => ['roles'],
//     'conditions'=> ['firstname:%Max%','is_deleted:0'],
//     'sort' => 'firstname|desc'
// ]);

// $users = UserModel::find([
//     'conditions'=> 'default_company = 3 and is_deleted = 0',
//     'order'=> 'firstname|desc',
//     'limit' => '2',
//     // 'bind' => ['max@mctekk.com']
// ]);

// $users = UserModel::findFirst([
//     'conditions'=> 'id = 2 and is_deleted = 0'
//     // 'order'=> 'firstname|desc',
//     // 'limit' => '2',
//     // 'bind' => ['max@mctekk.com']
// ]);

$users = UserModel::findFirst(2);

print_r($users);
die();
