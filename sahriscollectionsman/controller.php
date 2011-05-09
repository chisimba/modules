<?php
/**
 * SAHRIS Collections manager controller class
 *
 * Class to control the Collections manager module
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
 * @package   sahriscollectionsman
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
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
 * SAHRIS Collections manager controller class
 *
 * Class to control the Collections manager module.
 *
 * @category  Chisimba
 * @package   sahriscollectionsman
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class sahriscollectionsman extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objExif;
    public $objMarc;
    public $objRdf;
    public $objMongodb;
    public $objCollOps;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            // $this->requiresLogin();
            $this->objLanguage   = $this->getObject ( 'language', 'language' );
            $this->objConfig     = $this->getObject('altconfig', 'config');
            $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser       = $this->getObject('user', 'security');
            $this->objModuleCat  = $this->getObject('modules', 'modulecatalogue');
            $this->objRdf        = $this->getObject ('rdf', 'rdfgen');
            $this->objCollOps    = $this->getObject('sahriscollectionsops');
            $this->objDbColl     = $this->getObject('dbsahriscollections');

			// Define the paths we will be needing
			define ( "RDFAPI_INCLUDE_DIR", $this->getResourcePath ('api/', 'rdfgen'));
			include (RDFAPI_INCLUDE_DIR . "RdfAPI.php");

            if($this->objModuleCat->checkIfRegistered('activitystreamer'))
            {
                $this->objActStream = $this->getObject('activityops','activitystreamer');
                $this->eventDispatcher->addObserver(array($this->objActStream, 'postmade' ));
                $this->eventsEnabled = TRUE;
            } else {
                $this->eventsEnabled = FALSE;
            }
        }
        catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {
            case NULL:

            case 'addform' :
                // check that there is a collection to add to first...
                $cnames = $this->objDbColl->getCollectionNames();
                if(!empty($cnames)) {
                    $form = $this->objCollOps->addRecordForm();   
                }
                else {
                    $form = $this->objLanguage->languageText("mod_sahriscollectionsman_nocolldefined", "sahriscollectionsman");
                }
                $this->setVarByRef('form', $form);
                return 'add_tpl.php';
                break;
                
            case 'collform' :
                $form = $this->objCollOps->addCollectionForm();
                $this->setVarByRef('form', $form);
                return 'addcoll_tpl.php';
                break;

            case 'createcollection' :
                $collname = $this->getParam('cn');
                $comment = $this->getParam('comment');
                $insarr = array('userid' => $this->objUser->userId(), 'collname' => $collname, 'comment' => $comment);
                $this->objDbColl->insertCollection($insarr);
                $this->nextAction('');
                break;

            case 'addrec' :
                $acno = $this->getParam('ano');
                $coll = $this->getParam('coll');
                $title = $this->getParam('title');
                $desc = $this->getParam('desc');
                // $datecreated = $this->getParam('datecreated');
                $media = $this->getParam('media');
                $comment = $this->getParam('comment');
                $insarr = array('userid' => $this->objUser->userId(), 'accno' => $acno, 'collection' => $coll, 'title' => $title, 'description' => $desc, 'media' => $media, 'comment' => $comment);
                
                $res = $this->objDbColl->insertRecord($insarr);
                $this->nextAction('');
                break;

            case 'getrecord' :
                $acno = $this->getParam('acno');
                $coll = $this->getParam('coll');
                $res = $this->objDbColl->getSingleRecord($acno, $coll);
                $this->setVarByRef('res', $res);
                return 'viewsingle_tpl.php';
                break;
                
            case 'viewsingle' :
                $id = $this->getParam('id');
                $res = $this->objDbColl->getSingleRecordById($id);
                $this->setVarByRef('res', $res);
                return 'viewsingle_tpl.php';
                break;

            case 'search':
                $query = $this->getParam('q', NULL);
                if($query == NULL) {
                    $res = NULL;
                    return 'search_tpl.php';
                }
                else {
                    $res = $this->objDbColl->searchItems($query);
                    $this->setVarByRef('res', $res);
                    return 'search_tpl.php';
                }
                break;
                
            case 'searchform' :
                $form = $this->objCollOps->searchForm();
                $this->setVarByRef('form', $form);
                return 'search_tpl.php';
                break;

            default:
                $this->nextAction('');
                break;
        }
    }

}
?>