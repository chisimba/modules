<?php

if(!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

class jukskeicontent extends controller {
  public function init() {
    
  }
  
  public function dispatch($action) {
    return "home_tpl.php";
  }
  
  function requiresLogin(){
    return false;
  }

}


?>