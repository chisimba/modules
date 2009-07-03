<?php

class formerror extends object {
  var $errorarray = array();
  function init() {
  
  }
  
  function setError($field, $error) {
    $this->errorarray[$field] = $error;
  }
  
  function getError($field) {
    if (isset($this->errorarray[$field])) {
      return "<span style=\"color:red\">* " . $this->errorarray[$field] . "</span>";
    }
    else {
      return "";
    }
  }
  function numErrors() {
    return count($this->errorarray);
  }
}

?>
