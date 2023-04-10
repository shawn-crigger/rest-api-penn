<?php

namespace App\Helpers;

/**
 * Class to provide helper functions for validating input.
 */
class Validation_Helper {

  /**
   * Incase I decide to use this method.
   */
  public function __construct()
  {

  }


  /**
   * Validates ID is greater then zero.
   * @param mixed $id
   * @return bool
   */
  public function validateID($id) {
    $id = (int) $id;
    if (empty($id) && $id <= 0) {
      return false;
    }

    return true;
  }

  // ------------------------------------------------------------------------

  /**
   * Function to validate the name it could be expanded if needed to check for a space for first last name etc.
   * @param string $name
   * @return array
   */
  function validateName($name) {
    $errors = [];
    if (is_string($name) && strlen($name) <= 0) {
      $errors[] = ['success' => false, 'message' => "Name can't be empty"];
    }
    return $errors;
  }

  /**
   * Function to validate the email is not empty and follows proper PHP validation.
   * @param string $email
   * @return array
   */
  function validateEmail($email) {
    $errors = [];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (is_string($email) && strlen($email) <= 0) {
      $errors[] = ['success' => false, 'message' => "Email can't be empty"];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = ['success' => false, 'message' => "Invalid Email address."];
    }
    return $errors;
  }
}