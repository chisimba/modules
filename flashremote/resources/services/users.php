<?php
/**
* Manipulate the methodTable by writing javadoc comments on top of your methods. 
* The following tags are supported:
* @desc
* @access (set to remote to export a class)
* @roles (comma-separated)
* @instance
* @returns
* @pagesize
*
*/
define("AMFPHP_BASE", realpath(dirname(dirname(dirname(__FILE__)))) . "/");
$GLOBALS['kewl_entry_point_run'] = TRUE;
$GLOBALS['savedir'] = getcwd();

//Load the XML settings file
$xml = simplexml_load_file("settings.xml");
//$homeBase = $xml->chisimba_core_path->value;
$homeBase = '/home/dkeats/Desktop/eclipse-workspace/chisimba_framework/app/';
chdir($homeBase);
require_once 'classes/core/engine_class_inc.php';
//require_once 'classes/core/object_class_inc.php';
require_once 'classes/core/dbtable_class_inc.php';

/**
* 
* Created on 27 Oct 2007
* 
* AMFPHP service class return user information from Chisimba.
* @author Derek keats
* @package package_name
* 
*/
class users
{
    public function users()
    {
        die();
    }

    /**
    * 
    * Userdata method retrieves data for a particular user as stored in the 
    * tbl_users table.
    * @param string $username The username to look up.
    * @access remote
    * @returns A recordset containing a single record for the username given as a parameter.
    * 
    */
    public function userdata($username)
    {
        $eng = new engine;
        chdir($GLOBALS['savedir']);
        $objDt = new chisimbaConnector($eng, NULL);
        $ar = $objDt->getUser($username);
        return new RecordSet($ar);

    }

}

/**
*
* Chisimba based helper class that extends tbTable to provide
* the user data being looked up.It also makes use of the user 
* class from the security module. 
* 
*/
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
    
}
?>