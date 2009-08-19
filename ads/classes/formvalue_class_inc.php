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
  function getSaveScript($saveUrl) {
      $saveFormJS = 'jQuery(document).ready(function() {
                    jQuery("#saveMsg").hide();

                    jQuery("#saveBtn").click(function() {
                           data = jQuery("form").serialize();
                           url = "'.str_replace("amp;", "", $saveUrl).'";

                           jQuery.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function(msg) {
                                    jQuery("#saveMsg").show();
                                    jQuery("#saveMsg").text("Data saved successfully");
                                    jQuery("#saveMsg").fadeOut(5000);
                                }
                           });
                    });
              });';

    return "<script type='text/javascript'>".$saveFormJS."</script>";
  }
}

?>
