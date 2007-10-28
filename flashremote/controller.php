<?php
/**
 * 
 * A module to provide a flash remoting interface to Chisimba based on amfphp Flash remoting library.
 * 
 * This module provides for the use of ampphp for Flash remoting in Chisimba. Amfphp is an RPC toolkit for PHP. Amfphp allows seamless communication between Php and Flash and Flex with Remoting, JavaScript and Ajax with JSON, and XML clients with XML-RPC.
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
 * @author    Derek Keats dkeats@uwc.ac.za
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
* Controller class for Chisimba for the module flashremote
*
* @author Derek Keats
* @package flashremote
*
*/
class flashremote extends controller
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
    * Intialiser for the flashremote controller
    * @access public
    * 
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    
    /**
     * 
     * The standard dispatch method for the flashremote module.
     * The dispatch method uses methods determined from the action 
     * parameter of the  querystring and executes the appropriate method, 
     * returning its appropriate template. This template contains the code 
     * which renders the module output.
     * 
     */
    public function dispatch()
    {
        //Get action from query string and set default to view
        $this->action=$this->getParam('action', 'gateway');
        // retrieve the mode (edit/add/translate) from the querystring
        $mode = $this->getParam("mode", null);
        // retrieve the sort order from the querystring
        $order = $this->getParam("order", null);
        /*
        * Convert the action into a method (alternative to 
        * using case selections)
        */
        $method = $this->__getMethod($this->action);
        /*
        * Return the template determined by the method resulting 
        * from action
        */
        return $this->$method();
    }
    
    
    /*------------- BEGIN: Set of methods to replace case selection ------------*/

    /**
    * 
    * Method corresponding to the browser action. It allows you
    * to browse services on this computer.
    * 
    * @access private
    * 
    */
    private function __browser()
    {
        //Create the configuration object
        $objConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $browseAllowed = $objConfig->getValue("mod_flashremote_allowbrowse", "flashremote");
        if ($browseAllowed == "TRUE") {
            return "browser_tpl.php";
        } else {
            $str = "<div class=\"error\">" 
              . $this->objLanguage->languageText("mod_flashremote_browsenotallowed", "flashremote")
              . "</div>";
            $this->setVarByRef('str', $str);
            return "dump_tpl.php";
        }
    }
    
    private function __gateway()
    {
        define("PRODUCTION_SERVER", false);
        //Include things that need to be global, for integrating with other frameworks
        //require_once($this->getResourcePath('globals.php', 'flashremote'));
        //Include framework
        require_once($this->getResourcePath('core/amf/app/Gateway.php', 'flashremote'));
        //Instantiate the gateway
        $gateway = new Gateway();
        //Set where the services classes are loaded from, *with trailing slash*
        //$servicesPath defined in globals.php --- fix this to work with the framework
        $servicesPath = $this->getResourcePath('services/', 'flashremote');
        $voPath = $this->getResourcePath('services/vo/', 'flashremote');
        $gateway->setClassPath($servicesPath);
        //Read above large note for explanation of charset handling
        //The main contributor (Patrick Mineault) is French, 
        //so don't be afraid if he forgot to turn off iconv by default!
        $gateway->setCharsetHandler("utf8_decode", "ISO-8859-1", "ISO-8859-1");
        //Error types that will be rooted to the NetConnection debugger
        $gateway->setErrorHandling(E_ALL ^ E_NOTICE);
        if(PRODUCTION_SERVER)
        {
            //Disable profiling, remote tracing, and service browser
            $gateway->disableDebug();
        }
        //Enable gzip compression of output if zlib is available, 
        //beyond a certain byte size threshold
        $gateway->enableGzipCompression(25*1024);
        
        //Service now
        $gateway->service();
    }
    
    
    /**
    * 
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    * 
    */
    private function __test()
    {
        require_once($this->getResourcePath('core/amf/app/Gateway.php', 'flashremote'));
        $gateway = new Gateway();
        $servicesPath = "services/";
        $voPath = "services/vo/";
        //Set where the services classes are loaded from, *with trailing slash*
        //$servicesPath defined in globals.php
        $gateway->setClassPath($servicesPath);
        //Set where class mappings are loaded from (ie: for VOs)
        //$voPath defined in globals.php
        $gateway->setClassMappingsPath($voPath);
        $gateway->setCharsetHandler("utf8_decode", "ISO-8859-1", "ISO-8859-1");
        //Error types that will be rooted to the NetConnection debugger
        $gateway->setErrorHandling(E_ALL ^ E_NOTICE);
        //Disable profiling, remote tracing, and service browser
        $gateway->disableDebug();
        //Enable gzip compression of output if zlib is available, 
        //beyond a certain byte size threshold
        $gateway->enableGzipCompression(25*1024);
        //Service now
        $str="<h1>Results of test:</h1>" . $gateway->service();
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
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
          .": " . $this->action . "</h3>");
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
        $action=$this->getParam('action', NULL);
        switch ($action)
        {
            case NULL:
                return FALSE;
                break;
            default:
                return TRUE;
                break;
        }
     }
}
?>
