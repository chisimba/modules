<?php
define("AMFPHP_BASE", realpath(dirname(dirname(dirname(__FILE__)))) . "/");
$GLOBALS['kewl_entry_point_run'] = TRUE;
$GLOBALS['savedir'] = getcwd();


$homeBase = '/home/dkeats/Desktop/eclipse-workspace/chisimba_framework/app/';
chdir($homeBase);
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

/*$oT = new test();
echo $oT->currentUser();*/

$objBridge = new bridge;
$eng = $objBridge->startBridge();
chdir($GLOBALS['savedir']);
$objDt = new chisimbaConnector($eng, NULL);
$ar = $objDt->getUser('admin');
$fn = $ar[0]['firstname'];
$sn = $ar[0]['surname'];
echo $fn . " " . $sn;
?>