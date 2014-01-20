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
  // arguments are: field name, validation type, message, custom param
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

// apply the validator to your data
$errors = $validator(array(
  'email' => 'noodlehaus',
  'role' => 'developer'
));

// apply the validator to your model
$errors = $validator((array) $dataObject);

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
  array(2) {
    [0]=>
    string(28) "Password should not be empty"
    [1]=>
    string(36) "Password needs to match confirmation"
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
