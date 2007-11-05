<?php
//DO not change this line as it sets the directory where AMFPHP is located
define("AMFPHP_BASE", realpath(dirname(dirname(dirname(__FILE__)))) . "/");
//Include the required configuration information
require_once '../flashremote_config_inc.php';
//Set the home directory so we can change to it to load the singleton
$homeBase = CHISIMBA_BASEPATH;
//Change to the app directory to load the singleton bridge to Chisimba
chdir($homeBase);
//Include the Chisimba bridge class from the framework
require_once 'classes/core/bridge_class_inc.php';


/**
*
* Created on 27 Oct 2007
*
* AMFPHP service class return user information
*
* @author Derek keats
* @package package_name
*
*/
class test
{

    public function test()
    {
        $this->methodTable = array(
            "userdata" => array(
                "description" => "Gets the full name of the user accessing the Chisimba site.",
                "access" => "remote"
            )
        );
    }

    public function userdata($username)
    {
        $objBridge = new bridge;
        $eng = $objBridge->startBridge();
        chdir($GLOBALS['savedir']);
        $objDt = new chisimbaConnector($eng, NULL);
        $ar = $objDt->getUser($username);
        return new RecordSet($ar);

    }

    public function currentUser()
    {
        $objBridge = new bridge;
        $eng = $objBridge->startBridge();
        chdir($GLOBALS['savedir']);
        $objDt = new chisimbaConnector($eng, NULL);
        return $objDt->currentUser();
    }
}


class chisimbaConnector extends dbTable
{
    function init()
    {
        parent::init('tbl_users');
    }

    function getUser($username)
    {
        $objUser = $this->getObject("user", "security");
        $sql="SELECT
            tbl_users.username,
            tbl_users.userid,
            tbl_users.title,
            tbl_users.firstname,
            tbl_users.surname,
            tbl_users.creationdate,
            tbl_users.emailaddress,
            tbl_users.logins,
            tbl_users.isactive,
            tbl_users.accesslevel
        FROM
            tbl_users
        WHERE
            (username = '".addslashes($username)."')";
        $array=$this->getArray($sql);
        return $array;
    }

    public function currentUser()
    {
        $objUser=$this->getObject("user", "security");
        return $objUser->userName();
    }

}
?>