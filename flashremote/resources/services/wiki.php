<?php
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
* AMFPHP service class return user information
* 
* @author Derek keats
* @package package_name
* 
*/
class wiki
{

    public function wiki()
    {

    }
    
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
        $eng = new engine;
        //chdir($GLOBALS['savedir']);
        $objDt = new chisimbaConnector($eng, NULL);
        return $objDt->getPage($wiki, $page);
    }
    
    function whereami()
    {
        chdir($GLOBALS['savedir']);
        return getcwd();
    }
    

}


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