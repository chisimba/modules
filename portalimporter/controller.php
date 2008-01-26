<?php
/**
 *
 * Portal importer
 *
 * Portal importer was developed to import the static content from the UWC portal 
 * into Chisimba. Portal importer is not an end user module, but rather a tool for 
 * developers to work with to import a large volume of structured web content from 
 * static HTML into the Chisimba CMS. Do not have this module installed on a 
 * production server as it has NO SECURITY!
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   helloforms
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* Controller class for Chisimba for the module codetesting
*
* @author Derek Keats
* @package codetesting
*
*/
class portalimporter extends controller
{

    /**
    *
    * @var string $objConfig String object property for holding the
    * configuration object
    * @access public;
    *
    */
    public $objConfig;

    /**
    *
    * @var string $objLanguage String object property for holding the
    * language object
    * @access public
    *
    */
    public $objLanguage;
    /**
    *
    * @var string $objLog String object property for holding the
    * logger object for logging user activity
    * @access public
    *
    */
    public $objLog;

    /**
    *
    * Intialiser for the codetesting controller
    * @access public
    *
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        $this->config = $this->getObject('altconfig','config');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }


    /**
     *
     * The standard dispatch method for the codetesting module.
     * The dispatch method uses methods determined from the action
     * parameter of the  querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     */
    public function dispatch()
    {
        //Get action from query string and set default to view
        $action=$this->getParam('action', 'readportal');
        // Convert the action into a method
        $method = $this->__getMethod($action);
        //Return the template determined by the method resulting from action
        return $this->$method();
    }

    private function __readportal()
    {
        $rP = $this->getObject('portalfileutils', 'portalimporter');
        $start_dir = "/home/dkeats/websites/portal/www.uwc.ac.za";
        $level=1;  // level is the first level started at
        $last=3; //this is set the same as level so the script does not read all directories, and only one at a time
        $dirs = array();  // SET dirs as an ARRAY so it can be read
        $files = array(); //SET files as an ARRAY so it can be read
        $rP->readpath($start_dir,$level, $last, $dirs,$files);
        //$str .= $rP->showDirs();
        $str .= nl2br(htmlentities($rP->showFilesAsXML()));
        $str .= "<hr />";
        $str .= "<br />Directory size: ";
        $str .= $rP->getSize();
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }


    private function __tip()
    {
        $tt = $this->getObject('domtt', 'htmlelements');
        $tt->putScripts();
        $tt->url="http://www.uwc.ac.za";
        $tt->linkText="The University of the Western Cape";
        $tt->message = "Now is the time for all good men to come to the aid of the party. The quick brown fox jumped over the big brown lazy dog and fell on its head when it landed.";
        $tt->title = NULL;
        $str = $tt->show();
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }

    private function __modlist()
    {
        $objMods = $this->getObject('modulefile', 'modulecatalogue');
        $ar = $objMods->getLocalModuleList();
        $k=0;
        $str="";
        foreach ($ar as $key=>$moduleId) {
            $tags = $objMods->moduleTags($moduleId);
            $tCount = count($tags);
            $k++;
            $str .=  $k . ". " . $moduleId . " [<font color='red'>"
              . $tCount . " = " . $tags . "</font>]<br />";
            $tags=array();
        }
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";

    }

    private function __countries()
    {
        $objCountries=$this->getObject('languagecode','language');
        echo $objCountries->countryAlpha();
        die();
        $ar = $objCountries->countryListArr();
        asort($ar);
        //var_dump($ar); die();

        $this->loadClass('dropdown','htmlelements');
        $objSelect = new dropdown('country');

        foreach ($ar as $code=>$country) {
            //if ($code == "ZA") {
            //    echo "<font color='red'> $code</b>   is indeed ZA ($country)</font><br />";
                $objSelect->addOption($code, $country);
            //} else {
            //    echo $code . " is not ZA ($country)<br />" ;
                $objSelect->addOption($code, $country);
            //}

            //echo $code . " = " . $country ."<br />";
        }
        $objSelect->setSelected("ZA");
        echo $objSelect->show();
        die();
        $this->setVarByRef('ar', $ar);
        return 'dump_tpl.php';
    }


    /*------------- BEGIN: Set of methods to replace case selection ------------*/

    /**
    *
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    *
    */
    private function __defaulttest()
    {
        $objExpar = $this->getObject("extractparams", "utilities");
        $str=" a=b,c=d,e= f,";
        $ar= $objExpar->getArrayParams($str, ",");
        $this->setVarByRef('ar', $ar);
        $this->setVar("str", "A: " . $objExpar->a . "<br />");
        return "proplist_tpl.php";
    }

    /**
    *
    * Method to return an error when the action is not a valid
    * action method
    *
    * @access private
    * @return string The dump template populated with the error message
    *
    */
    private function __actionError()
    {
        $this->setVar('str', "<h3>"
          . $this->objLanguage->languageText("phrase_unrecognizedaction")
          .": " . $action . "</h3>");
        return 'dump_tpl.php';
    }

    /**
    *
    * Method to check if a given action is a valid method
    * of this class preceded by double underscore (__). If it __action
    * is not a valid method it returns FALSE, if it is a valid method
    * of this class it returns TRUE.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return boolean TRUE|FALSE
    *
    */
    function __validAction(& $action)
    {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    *
    * Method to convert the action parameter into the name of
    * a method of this class.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return stromg the name of the method
    *
    */
    function __getMethod(& $action)
    {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }

    /*------------- END: Set of methods to replace case selection ------------*/



    /**
    *
    * This is a method to determine if the user has to
    * be logged in or not. Note that this is an example,
    * and if you use it view will be visible to non-logged in
    * users. Delete it if you do not want to allow annonymous access.
    * It overides that in the parent class
    *
    * @return boolean TRUE|FALSE
    *
    */
    public function requiresLogin()
    {
        $action=$this->getParam('action','NULL');
        switch ($action)
        {
            case 'view':
                return FALSE;
                break;
            default:
                return TRUE;
                break;
        }
     }
}
?>
