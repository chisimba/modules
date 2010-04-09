<?php

/**
 * Triplestore controller class
 * 
 * Class to control the Triplestore module
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
 * @category  chisimba
 * @package   triplestore
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php 16050 2009-12-26 01:34:10Z charlvn $
 * @link      http://avoir.uwc.ac.za/
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Triplestore controller class
 *
 * Class to control the Triplestore module
 *
 * @category  Chisimba
 * @package   triplestore
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za/
 */
class triplestore extends controller
{
    /**
     * Instance of the dbtriplestore class of the triplestore module.
     *
     * @access protected
     * @var    object
     */
    protected $objTriplestore;
    /**
    *
    * @var string $objLanguage String object property for holding the
    * language object
    * @access public
    *
    */
    public $objLanguage;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables.
     *
     * @access public
     */
    public function init()
    {
        $this->objTriplestore = $this->getObject('dbtriplestore', 'triplestore');
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
     * Standard dispatch method to handle the various possible actions.
     *
     * @access public
     */
    public function olddispatch()
    {
        $filters     = array();
        $filterTypes = array('subject', 'predicate', 'object');

        foreach ($filterTypes as $filterType) {
            $filter = $this->getParam($filterType);
            if ($filter) {
                $filters[$filterType] = $filter;
            }
        }

        $nestedTriples = $this->objTriplestore->getNestedTriples($filters);

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($nestedTriples);
    }

    public function dispatch()
    {
        // Get action from query string and set default to view.
        $action=$this->getParam('action', 'view');
        // Convert the action into a method.
        $method = $this->__getMethod($action);
        // Return the template determined by the action.
        return $this->$method();
    }

    /**
    *
    * Method corresponding to the view action.
    * @access private
    *
    */
    private function __view()
    {
        $filters = array();
        $filterTypes = array('id', 'subject', 'predicate', 'object');
        foreach ($filterTypes as $filterType) {
            $filter = $this->getParam($filterType);
            if ($filter) {
                $filters[$filterType] = $filter;
            }
        }
        $format = $this->getParam('format', 'nested');
        switch ($format) {
            case 'nested':
                $triples = $this->objTriplestore->getNestedTriples($filters);
                break;
            case 'flat':
                $triples = $this->objTriplestore->getTriples($filters);
                break;
            default:
                $triples = FALSE;
        }
        if (is_array($triples)) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($triples);
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    private function __getmytripples()
    {
        //just testing here
        $objTripleUi = $this->getObject('tripleui', 'triplestore');
        echo $objTripleUi->getMyTriples();
        die();
    }

    /**
    *
    * Method corresponding to the edit action. It sets the mode to
    * edit and returns the edit template.
    * @access private
    *
    */
    private function __edit()
    {
        $this->setvar('mode', "edit");
        return 'editform_tpl.php';
    }

    /**
    *
    * Method corresponding to the asjson action.  It gets a specific
    * set of triples as json
    *
    * @access private
    *
    */
    private function __asjson()
    {
        $this->setvar('str', "JSON output will be here");
        return 'dump_tpl.php';
    }

    /**
    *
    * Method corresponding to the add action. It sets the mode to
    * add and returns the edit content template.
    * @access private
    *
    */
    private function __add()
    {
        $this->setvar('mode', 'add');
        return 'editform_tpl.php';
    }

    /**
    *
    * Method corresponding to the save action. 
    *
    * @access private
    *
    */
    private function __save()
    {
        $mode = $this->getParam("mode", NULL);
        $subject = $this->getParam('subject', NULL);
        $predicate = $this->getParam('predicate', NULL);
        $tripobject = $this->getParam('tripobject', NULL);
        if ($mode=="edit") {
            $id = $this->getParam('id', NULL);
            die("EDIT");
            // Update the existing record
            //@todo
        } else {
            die($this->objTriplestore->insert($subject, $predicate, $tripobject));
        }
        return $this->nextAction(NULL);
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
          .": " . $this->getParam('action', NULL) . "</h3>");
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

    /**
     * Overide the login object in the parent class.
     *
     * @access public
     * @param  string $action The name of the action
     * @return bool
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
