<?php
/**
 * Creates a validation routine using the passed rules. Apply this
 * routine to your data to get back the errors.
 */
function darth() {
  $rules = func_get_args();
  return function ($data) use ($rules) {
    $errors = array();
    foreach ($rules as $rule) {
      list($name, $handler, $message) = $rule;
      if (!$handler($data))
        $errors[$name][] = $message;
    }
    return $errors;
  };
}

/**
 * Creates a validation rule. Some rules require 4 arguments,
 * others, 3. Last argument is always the message.
 */
function force($type, $name, $msg_or_arg, $msg_or_null = null) {

  $types = explode('|', strtolower($type));

  if (in_array('required', $types)) {
    $cond_1 = function ($data) use ($name) {
      if (!isset($data[$name]) || !strlen($data[$name]))
        return false;
      return true;
    };
  } else {
    $cond_1 = function () { return true; };
  }

  if (in_array('confirmed', $types)) {
    $cond_2 = function ($data) use ($name, $msg_or_arg) {
      $a = isset($data[$name]) ? $data[$name] : null;
      $b = isset($data[$msg_or_arg]) ? $data[$msg_or_arg] : null;
      return ($a == $b);
    };
  } elseif (in_array('email', $types)) {
    $cond_2 = function ($data) use ($name) {
      $value = isset($data[$name]) ? $data[$name] : '';
      return ($value == filter_var($value, FILTER_VALIDATE_EMAIL));
    };
  } elseif (in_array('regex', $types)) {
    $cond_2 = function ($data) use ($name, $msg_or_arg) {
      return isset($data[$name]) && preg_match($msg_or_arg, $data[$name]);
    };
  } elseif (in_array('custom', $types)) {
    $cond_2 = function ($data) use ($name, $msg_or_arg) {
      return isset($data[$name]) && $msg_or_arg($data[$name]);
    };
  } else {
    $cond_2 = function () { return true; };
  }

  $routine = function ($data) use ($cond_1, $cond_2) {
    return $cond_1($data) && $cond_2($data);
  };

  return array($name, $routine, $msg_or_null ? $msg_or_null : $msg_or_arg);
}
?>
