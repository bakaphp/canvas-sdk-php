<?php

require_once 'vendor/autoload.php';

use Canvas\Canvas;
use Canvas\Resources\Users;
use Canvas\Resources\Auth;

Canvas::setApiKey('asdeaefaefaefae');

// $users = Users::create([
//     'firstname'=>'testSDK',
//     'lastname'=> 'testSDK',
//     'displayname'=> 'sdktester',
//     'password'=> 'nosenose',
//     'default_company'=> 'example sdk',
//     'email'=> 'examplesdk@gmail.com',
//     'verify_password'=> 'nosenose'
//     ]);

// $users = Users::update('87',[
//     'firstname'=>'testSDK',
//     'lastname'=> 'testSDK',
//     ]);



// $users = Users::delete('87');


$auth = Auth::auth(['email'=> 'max@mctekk.com','password'=>'nosenose']);

$users = Users::all();

print_r($users);
die();
