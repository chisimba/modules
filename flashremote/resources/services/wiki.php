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
* AMFPHP service class to return a wiki page from
* the chisimba wiki
*
* @author Derek keats
* @package flashremote
*
*/
class wiki
{

    /**
    *
    * getPage method retrieves data for a particular user as stored in the
    * tbl_users table. Try calling with wiki=Default wiki and page=MainPage.
    * @param string $wiki The wiki namespace to look in.
    * @param string $pageCode The CamelCasePage to return.
    * @access remote
    * @returns A string containing the content of the wikipage given as a parameter.
    *
    */
    public function getPage($wiki, $page)
    {
        $objBridge = new bridge;
        $eng = $objBridge->startBridge();
        //chdir($GLOBALS['savedir']);
        $objDt = new chisimbaConnector($eng, NULL);
        return $objDt->getPage($wiki, $page);
    }
}

/**
*
* Chisimba class to actually go get the wiki page.
*
* @access public
* @return string the wiki page being requested
* @author Derek keats
* @package flashremote
*
*/
class chisimbaConnector extends object
{
    function init()
    {
    }

    function getPage($wiki, $page)
    {
        //Get the wiki display object
        $objWiki = $this->newObject('wikidisplay', 'wiki');
        //Get the page
        return $objWiki->showPage($wiki, $page);
    }
}


?>