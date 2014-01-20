<?php
/**
 * I find your lack of validation... disturbing.
 *
 * @author Jesus A. Domingo <jesus.domingo@gmail.com>
 * @license MIT <http://noodlehaus.mit-license.org>
 */

/**
 * Ask Darth Validator to enforce certain rules. Returns
 * a function which will be used for validation.
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
 * Create a rule that will be given to the Darth for enforcement.
 * Returns a callable that performs the check.
 */
function force($name, $type, $message, $custom = null) {

  $handler = null;

  switch ($type) {

  case 'required':
    $handler = function ($data) use ($name, $custom) {
      if (!isset($data[$name]) || !strlen($data[$name]))
        return false;
      return true;
    };
    break;

  case 'email':
    $handler = function ($data) use ($name) {
      $value = isset($data[$name]) ? $data[$name] : '';
      return ($value == filter_var($value, FILTER_VALIDATE_EMAIL));
    };
    break;

  case 'confirmation':
    $handler = function ($data) use ($name, $custom) {
      $a = isset($data[$name]) ? $data[$name] : null;
      $b = isset($data[$custom]) ? $data[$custom] : null;
      return (strlen($a) && ($a == $b));
    };
    break;

  case 'regex':
    $handler = function ($data) use ($name, $custom) {
      return isset($data[$name]) && preg_match($custom, $data[$name]);
    };
    break;

  case 'custom':
    $handler = function ($data) use ($name, $custom) {
      return isset($data[$name]) && $custom($data[$name]);
    };
    break;

  default:
    trigger_error("force() type is not supported", E_USER_ERROR);
  }

  return array($name, $handler, $message);
}
?>
