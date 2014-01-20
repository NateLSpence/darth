<?php
require __DIR__."/darth.php";

$validator = darth(
  force('email', 'email', 'Email is invalid'),
  force('username', 'required', 'Username is required'),
  force('password', 'required', 'Password should not be empty'),
  force('age', 'regex', 'That is not a number!', '/^[0-9]+$/'),
  force(
    'password',
    'confirmation',
    'Password needs to match confirmation',
    'password_confirmation'
  ),
  force('role', 'custom', 'Come to the dark side!', function ($role) {
    return $role == 'sith';
  })
);

$errors = $validator(array(
  'email' => 'noodlehaus',
  'role' => 'developer'
));

var_dump($errors);
?>
