<?php
define("AMFPHP_BASE", realpath(dirname(dirname(dirname(__FILE__)))) . "/");

/**
* Created on 27 Oct 2007
*
* amfphp service class to render hello world example
*
* @author Derek keats
* @package package_name
*
*/
class helloworld{

    /**
    *
    * @desc This sends back whatever is passed to it, with You said: in front of it
    * @access remote
    * @return string The message echoed back to you
    * @param string $message The message you sent
    *
    */
    public function makeEcho($message){
        return "You said: " . $message;
    }
}
?>
