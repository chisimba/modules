<?php
/**
 *
 * Empty module for testing code
 *
 * Empty module for testing code
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
 * @version   $Id: controller.php,v 1.4 2007-11-25 09:13:27 dkeats Exp $
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
class codetesting extends controller
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
        //$this->ajax = $this->getObject('ajax', 'codetesting');$groupId = $this->getParam('id', null);
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
	$this->objaltConfig = $this->getObject('altconfig', 'config');
        $this->objUpload = $this->getObject('upload', 'filemanager');
        $this->objFolders = $this->getObject('dbfolder', 'filemanager');
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
        $action=$this->getParam('action', 'formtest');
        // retrieve the mode (edit/add/translate) from the querystring
        $mode = $this->getParam("mode", null);
        // retrieve the sort order from the querystring
        $order = $this->getParam("order", null);
        /*
        * Convert the action into a method (alternative to
        * using case selections)
        */
        $method = $this->__getMethod($action);
        /*
        * Return the template determined by the method resulting
        * from action
        */
        return $this->$method();
    }

    private function __formtest()
    {
        $objForm = $this->newObject('htmlform', 'htmldom');
        $formAction = str_replace("&amp;", "&", $this->uri(array("action" => "formresults"), "codetest"));
        $objForm->createForm("testform", "myform", $formAction);
        $objForm->addElement("input", "blabla",  "blablabla", "Enter you's name, yea", array("maxlength" =>"2", "type" => "text", "class" => "sexybutton#search"));
        $objForm->addElement("textarea", "something", "something2", "Enter your life story");
        $optionsArray = array(array("One","FirstOne", FALSE), array("Two","SecondOne",TRUE), array("Three","Three",FALSE));
        $objForm->addElement("select", "myselecttest", "wadawada", "Select your selection", array("options" => $optionsArray, "multiple" => TRUE, "size" => "2"));
        $objForm->addButton("Le button de moi");
        $str = $objForm->show();
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }

    private function __buttontest()
    {

        $time_start = microtime(true);
        $this->loadClass('button', 'htmlelements');
        $this->objButton=new button('buttonname');
        $this->objButton->setOnClick('alert(\'An onclick Event\')');
        $this->objButton->setValue('TEST OF HTMLELEMENTS');
        $this->objButton->setId("what_a_long_id_hey2");
        $str = "<br />" . $this->objButton->show() . "<br />";
        $time_end = microtime(true);
        $time = ($time_end - $time_start) * 1000;
        $str = $str . "<br />" . $time . "<br />";

        
        $time_start = microtime(true);
        $objButt = $this->newObject('htmlbutton', 'htmldom');
        $objButt->setValue("cssId","what_a_long_id_hey");
        //$objButt->setValue("sexyButtons",FALSE);
        $objButt->setValue('onclick','javascript:alert("Some alert");');
        $str .= $objButt->show("TEST SUCCESSFUL YAY YAY YIPPEE");
        $time_end = microtime(true);
        $time = ($time_end - $time_start) * 1000;
        $str = $str . "<br />" . $time;
        unset($objButt);


        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }


    private function __blocktest()
    {
        $this->setVar('str', "Testing some block stuff.");
        return "dump_tpl.php";
    }


    /*------------- BEGIN: Set of methods to replace case selection ------------*/
    private function __truncate()
    {
        $objTruncate = $this->getObject('jqtruncate', 'htmlelements');
        $txt="<div class=\"shorter\">Now is the time for all good cookie monsters to eat at Google at Alices Restaurant where you can get anything that you want. Just come on in, sit right down, just have a mile from the mean old town. Walk rite up and have a blast, just don't eat it all too fast.</div>";
        $str = $objTruncate->show($txt, "shorter", 40, "More...", "...Less");
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }

    private function __view()
    {
        $objMk = $this->getObject('markitup', 'htmlelements');
        $setType = $this->getParam('set', 'chiki');
        $objMk->setTYpe($setType);
        $this->appendArrayVar('headerParams',$objMk->show('id', 'markItUp'));
        //echo nl2br(htmlentities($objMk->show()));
        //die();
        $str = "<textarea class='blabla' id=\"markItUp\"></textarea>";
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }
//Upload using ajax and file manager functionality
    private function __ajaxuploadread()
    {
        $objMk = $this->getObject('markitup', 'htmlelements');
        $setType = $this->getParam('set', 'chiki');
        $objMk->setTYpe($setType);
        $this->appendArrayVar('headerParams',$objMk->show('id', 'markItUp'));
/*
	$mysuccessMessage = $successMessage;
	$myerrorMessage = $errorMessage;
	$myoverwriteMessage = $overwriteMessage;
	$theseMessages = $this->getParam('messages', Null);
	$this->setVarByRef('messages',$theseMessages);		
	$test = "Just testing";
	$this->setVarByRef('mytest',$test);		
	$this->setVarByRef('successMessage',$mysuccessMessage);		
	$this->setVarByRef('errorMessage',$myerrorMessage);		
	$this->setVarByRef('overwriteMessage',$myoverwriteMessage);	
	//return "ajaxuploadread_tpl.php";
*/
	return "fileupload_tpl.php";
    }
//modified the upload function for use in wiki, upload and read textfile
    private function __uploadfileajax()
    {

        // Upload Files
	$fileupload = $this->getParam('fileupload');
        $results = $this->objUpload->uploadFiles();

        // Check if User entered page by typing in URL
        if ($results == FALSE) {
                echo 'failed';
        } else {
                echo 'ok';
        }
    }
//View ajax php form
    private function __ajaxread()
    {
        $str = "<textarea class='blabla' id='myName'></textarea><div id='divMessage' /><br />";
	
        $this->setVarByRef('str', $str);
        return "quickstart_tpl.php";
    }
    private function __ajaxstart()
    {
	$myName = $this->getParam('myName', Null);
	$this->setVarByRef('myName',$myName);
	
	$myName = $this->getParam('divMessage', Null);
	$this->setVarByRef('divMessage',$divMessage);
	return "ajaxstart_tpl.php";
    }
    private function __simpleajax()
    {
	return "index_tpl.php";
    }
    private function __ajaxchat()
    {
	return "ajaxchat_tpl.php";
    }

//View ajax php form
    private function __ajaxview()
    {
         $str = "<textarea class='blabla' id='myName'></textarea><div id='divMessage' /><br />";
        $this->setVarByRef('str', $str);
        return "quickstart_tpl.php";
    }
    /**
    *
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    *
    */
    private function __xiew()
    {

        $str = "Now is the time for all good cookie monsters to eat at Google at http://www.google.com. In
the merry month of May when the birds began to play, I took a walk quite early one fine morning at
<a href=\"http://www.gnu.org\">the Free Software Foundation</a>. There I found that there was
something http://www.gnu.org/graphics/gnu-head.jpg gnu to see. This image link (
<a href=\"http://www.gnu.org/graphics/gnu-head.jpg\">this one</a>) is not touched
because it is inside an ANCHOR tag. This is an image here:<br /><img src='http://farm3.static.flickr.com/2087/2148979646_413f5fa1c8.jpg' />" .
"<br />with a http part inside it." .
"<ul><li>http://www.google.com</li></ul>" .
        "inside a LI item";

        $washer = $this->getObject('url', 'strings');
        $str = $washer->mC($str);
        $str = $washer->tagExtLinks($str);
        $this->setVarByRef('str', $str);
        return "dump_tpl.php";
    }

        /**
    *
    * Method corresponding to the view action. It fetches the stories
    * into an array and passes it to a main_tpl content template.
    * @access private
    *
    */
    private function __viewxxx()
    {
        $str="<h1>WORKING HERE</h1>";
        $oT = $this->getObject("colorbox", "utilities");
        $str = $oT->show("yellowbox", "Now is the time for all good women and men to come to the aid of the party.");
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
