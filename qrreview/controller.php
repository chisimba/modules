<?php
/**
 * QRreview controller class
 *
 * Class to control the QRreview module
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
 * @package   qrreview
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
 * QRreview controller class
 *
 * Class to control the QRreview module.
 *
 * @category  Chisimba
 * @package   qrreview
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class qrreview extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objQrOps;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->objLanguage   = $this->getObject ( 'language', 'language' );
            $this->objConfig     = $this->getObject('altconfig', 'config');
            $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser       = $this->getObject('user', 'security');
            $this->objModuleCat  = $this->getObject('modules', 'modulecatalogue');
            $this->objQrOps      = $this->getObject('qrops', 'qrcreator');
            $this->objDbQr       = $this->getObject('dbqr', 'qrcreator');
            $this->objReviewOps  = $this->getObject('reviewops');
            $this->objDbReview   = $this->getObject('dbqrreview');
			
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

            case 'new' :
                $createbasic = $this->objReviewOps->addForm();
                // $createbasic = $this->objQrOps->geoLocationForm(); //$this->objQrOps->showInviteForm();
                $this->setVarByRef('createbasic', $createbasic);
                return 'newproduct_tpl.php';
                break;

            case 'addprod' :
                $longdesc = $this->getParam('longdesc');
                $prodname = $this->getParam('prodname');
                $userid = $this->objUser->userId();
                
                $recarr = array('longdesc' => $longdesc, 'prodname' => $prodname, 'userid' => $userid);
                $recid = $this->objDbReview->insertRecord($recarr);
                
                $url = $this->uri(array('id' => $recid, 'action' => 'mobireview'));
                $data = $this->objReviewOps->genBasicQr($userid, $url);
                $this->nextAction('details', array('id' => $recid));
                break;
                
            case 'details' :
                $id = $this->getParam('id');
                $row = $this->objDbReview->getRecord($id);
                if(!isset($row[0])) {
                    return 'notfound_tpl.php';
                    break;
                }
                else {
                    $row = $row[0];
                }
                $this->setVarByRef('row', $row);
                return 'detailview_tpl.php';
                break;
                
            case 'mobireview' :
                // mobile clients will come here via the QR code
                $id = $this->getParam('id');
                $row = $this->objDbReview->getRecord($id);
                if(!isset($row[0])) {
                    return 'notfound_tpl.php';
                    break;
                }
                else {
                    $row = $row[0];
                }
                // $this->setVarByRef('row', $row);
                // send out a form for mobile to do the review
                // return 'mobireview_tpl.php';
                echo $this->objReviewOps->showReviewFormMobi($row);
                break;
                
            case 'addreview' :
                // for either mobi or site based reviews, end point is the same
                $prodrate = $this->getParam('prodrate');
                $prodcomm = $this->getParam('prodcomm');
                $phone = $this->getParam('phone');
                
                $data = array('prodrate' => $prodrate, 'prodcomm' => $prodcomm, 'phone' => $phone);
                var_dump($data);
                
                break;
                
            case 'review' :
                // case for on site reviews
                break;
            

            default:
                $this->nextAction('');
                break;
        }
    }

    /**
     * Method to turn off login for selected actions
     *
     * @access public
     * @param string $action Action being run
     * @return boolean Whether the action requires the user to be logged in or not
     */
    function requiresLogin($action='review') {
        $allowedActions = array('mobireview', 'details', 'addreview');

        if (in_array($action, $allowedActions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
?>
