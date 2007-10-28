<?php
define("AMFPHP_BASE", realpath(dirname(dirname(dirname(__FILE__)))) . "/");
//include_once(AMFPHP_BASE . 'shared/adapters/Arrayf.php');
$GLOBALS['kewl_entry_point_run'] = TRUE;
$GLOBALS['disable_log_lib_in_dbtable'] = TRUE;
// initialise the engine object
require_once '/home/dkeats/Desktop/eclipse-workspace/chisimba_framework/app/classes/core/object_class_inc.php';
require_once '/home/dkeats/Desktop/eclipse-workspace/chisimba_framework/app/classes/core/dbtable_class_inc.php';
/**
* Created on 27 Oct 2007
* 
* amfphp service class to render hello world
* 
* @author Derek keats
* @package package_name
* 
*/

class helloworld{
  function helloworld(){
    $this->methodTable = array(
            "makeEcho" => array(
                "description"     => "Echoes the passed argument back to Flash (no need to set the return type)",
                "access"         => "remote",         // available values are private, public, remote
                "arguments"     => array ("arg1")
            )
    );
  }
  function makeEcho($message){
    return $message;
  }
}
?>
