<?php
require __DIR__."/darth.php";

$validator = darth(
  force('required|email', 'email', 'Email is invalid'),
  force('required', 'username', 'Username is required'),
  force(
    'confirmed',
    'password',
    'password_confirmation',
    'Password is invalid or not confirmed'
  ),
  force(
    'required|regex',
    'age',
    '/^[0-9]+$/',
    'That is not a number!'
  ),
  force(
    'required|custom',
    'role',
    function ($role) { return $role == 'sith'; },
    'Come to the dark side!'
  )
);

$errors = $validator(array(
  'email' => 'noodlehaus',
  'role' => 'developer',
  'password' => 'abc',
  'password_confirmation' => '123'
));

var_dump($errors);
?>
