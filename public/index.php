<?php

use Framework\Support\ExceptionHandler;

require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\User;

//$users = User::all();
//echo "<pre>";
//dump($users);
//echo "</pre>";
//
//$user = User::find(1);
//echo "<pre>";
//dump($user);
//echo "</pre>";

$newUser = [
    'username' => 'testuser',
    'email' => 'test@eample.com',
    'password' => password_hash('password', PASSWORD_BCRYPT),
];
//User::create($newUser);

User::update(1, ['email' => 'updated@example.com']);

User::delete(2);





$exceptionHandler = new ExceptionHandler();

set_exception_handler([$exceptionHandler, 'handle']);

