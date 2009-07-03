<?php

class formvalue extends object {
  var $valuearray = array();
  function init() {
  
  }
  function setValue($field, $value) {
    $this->valuearray[$field] = $value;
  }
  function getValue($field) {
    if (isset($this->valuearray[$field])) {
      return $this->valuearray[$field];
    }
    else {
      return "";
    }
  }
  function numValues() {
    return count($this->valuearray);
  }
  function setAllValues($valuearray) {
    $this->valuearray = $valuearray;
  }
}

?>
