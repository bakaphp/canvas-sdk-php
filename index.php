<?php

require_once 'vendor/autoload.php';

use Canvas\Canvas;
use Canvas\Resources\Users;
use Canvas\Resources\Companies;
use Canvas\Resources\Auth;


Canvas::setApiKey('asdeaefaefaefae');
$auth = Auth::auth(['email'=> 'max@mctekk.com','password'=>'nosenose']);

/**
 * Create a new user
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
 * Update user info by its id
 */
// $users = Users::update('84',[
//     'firstname'=>'testSDK',
//     'lastname'=> 'testSDK',
//     ]);



/**
 * Delete user by its id
 */
// $users = Users::delete('99');


/**
 * Retrieve a user by its id
 */
$users = Users::retrieve('2');

/**
 * Retrieve all users
 */
// $users = Users::all();

print_r($users);
die();
