# darth

"I find your lack of validation... disturbing." -- Darth Validator

`darth()` is a very simple procedural data validation tool for PHP.

To use this, you only need to make `darth()` validator use the `force()`.

Currently, the only supported validation types are:

* required
* email
* confirmation
* regex
* custom callbacks

Check the example below and the source for reference.

## example

The example below shows all that you can do with this.

```php
<?php
require __DIR__."/darth.php";

$validator = darth(
  // all rules are optional, unless flagged with 'required'
  force(
    'required|email',
    'email', // <-- name of the field to check
    'Email is invalid'
  ),
  force('required', 'username', 'Username is required'),
  // some rules require 4 args, but message is always last
  force(
    'confirmed',
    'password',
    'password_confirmation', // <-- the field to check for confirmation
    'Password is invalid or not confirmed'
  ),
  force(
    'required|regex',
    'age',
    '/^[0-9]+$/', // <-- your regex to use during validation
    'That is not a number!'
  ),
  force(
    'required|custom',
    'role',
    function ($role) { return $role == 'sith'; }, // <-- custom callable
    'Come to the dark side!'
  )
);

// use it against an array
$errors = $validator(array(
  'email' => 'noodlehaus',
  'role' => 'developer',
  'password' => 'abc',
  'password_confirmation' => '123'
));

// or cast your object into it
// $errors = $validator((object) $model);

// get your array of fields and messages
var_dump($errors);
?>
```

output will be:

```
array(5) {
  ["email"]=>
  array(1) {
    [0]=>
    string(16) "Email is invalid"
  }
  ["username"]=>
  array(1) {
    [0]=>
    string(20) "Username is required"
  }
  ["password"]=>
  array(1) {
    [0]=>
    string(36) "Password is invalid or not confirmed"
  }
  ["age"]=>
  array(1) {
    [0]=>
    string(21) "That is not a number!"
  }
  ["role"]=>
  array(1) {
    [0]=>
    string(22) "Come to the dark side!"
  }
}
```

## license

MIT <http://noodlehaus.mit-license.org>
