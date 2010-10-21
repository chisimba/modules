<?php
/**
 *
 * qrreview helper class
 *
 * PHP version 5.1.0+
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
 * @package   qrreview
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (! /**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 *
 * qrreview helper class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package qrreview
 *
 */
class reviewops extends object {

    /**
     * @var string $objLanguage String object property for holding the language object
     *
     * @access public
     */
    public $objLanguage;

    /**
     * @var string $objConfig String object property for holding the config object
     *
     * @access public
     */
    public $objConfig;

    /**
     * @var string $objSysConfig String object property for holding the sysconfig object
     *
     * @access public
     */
    public $objSysConfig;

    /**
     * @var string $objUser String object property for holding the user object
     *
     * @access public
     */
    public $objUser;
    
    /**
     * @var string $objCurl String object property for holding the cURL object
     *
     * @access public
     */
    public $objCurl;
    
    /**
     * @var string $objDbQr String object property for holding the QR db object
     *
     * @access public
     */
    public $objDbQr;
    

    /**
     * Constructor
     *
     * @access public
     */
    public function init() {
        $this->objLanguage   = $this->getObject('language', 'language');
        $this->objConfig     = $this->getObject('altconfig', 'config');
        $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->objUser       = $this->getObject('user', 'security');
        $this->objCurl       = $this->getObject('curl', 'utilities');
        $this->objDbQr       = $this->getObject('dbqr', 'qrcreator');
        $this->objCookie     = $this->getObject('cookie', 'utilities');
    }
    
    public function addForm() {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'qrreview', 'Required').'</span>';
        
        $ret = NULL;
        
        // start the form
        $form = new form ('prod', $this->uri(array('action'=>'addprod'), 'qrreview'));
        
        // add some rules
        $form->addRule('prodname', $this->objLanguage->languageText("mod_qrreview_needname", "qrreview"), 'required');
        
        $table = $this->newObject('htmltable', 'htmlelements');
        
        // product/service name
        $table->startRow();
        $prodname = new textinput('prodname');
        $prodnameLabel = new label($this->objLanguage->languageText('prodname', 'qrreview').'&nbsp;', 'input_prodname');
        $table->addCell($prodnameLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($prodname->show().$required);
        $table->endRow();
        
        // long desc field
        $defmsg = $this->objLanguage->languageText("mod_qrreview_longdesc", "qrreview");
        $table->startRow();
        $msg = $this->newObject('htmlarea', 'htmlelements');
        $msg->name = 'longdesc';
        $msg->value = $defmsg;
        $msg->width ='50%';
        
        $msgLabel = new label($this->objLanguage->languageText('mod_qrreview_proddesc', 'qrreview').'&nbsp;', 'input_msg');
        $table->addCell($msgLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($msg->show());
        $table->endRow();
        
        // do the fieldset thang
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
        $fieldset->contents = $table->show();
        
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_qrreview_createcode", "qrreview"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();
        
        return $ret;
    }
    
    /**
     * Method to generate a QR Code containing just a message
     *
     * @access public
     * @param string userid A Chisimba userid, got from objUser->userId()
     * @param string $msg a message up to 4MB long to encode
     * @return string $imgsrc
     */
    public function genBasicQr($userid, $msg) {
        $gmapsurl = NULL;
        $lat = NULL;
        $lon = NULL;
        // insert the message to the database and generate a url to use via a browser
        $recordid = $this->objDbQr->insert(array('userid' => $userid, 'msg' => $msg, 'lat' => $lat, 'lon' => $lon, 'gmapsurl' => $gmapsurl));
        // curl the Google Charts API to create the code
        $url = 'http://chart.apis.google.com/chart?chs=200x180&cht=qr&chl='.$msg;
        $image = $this->objCurl->exec($url);
        $basename = 'qr'.$recordid.'.png';
        $filename = $this->objConfig->getcontentBasePath().'users/'.$userid.'/'.$basename;
        $file = file_put_contents($filename, $image);
        // get the image path now
        $imgsrc = $this->objConfig->getsiteRoot().$this->objConfig->getcontentPath().'users/'.$this->objUser->userId().'/'.$basename;
        // return the useful stuff
        $ret = array('imageid' => $recordid, 'filename' => $imgsrc, 'userid' => $userid, 'basename' => $basename);
        return $ret;
    }
    
    /**
     * Sign in block
     *
     * Used in conjunction with the welcome block as a alertbox link. The sign in simply displays the block to sign in to Chisimba
     *
     * @return string
     */
    public function showSignInBox() {
        $objBlocks = $this->getObject('blocks', 'blocks');
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        return $objFeatureBox->show($this->objLanguage->languageText("mod_qrreview_signin", "qrreview"), $objBlocks->showBlock('login', 'security', 'none'));
    }

    /**
     * Sign up block
     *
     * Method to generate a sign up (register) block for the module. It uses a linked alertbox to format the response
     *
     * @return string
     */
    public function showSignUpBox() {
        $objBlocks = $this->getObject('blocks', 'blocks');
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        return $objFeatureBox->show($this->objLanguage->languageText("mod_qrreview_signup", "qrreview"), $objBlocks->showBlock('register', 'security', 'none'));
    }
    
    public function showReviewFormMobi($row) {
        $ret = NULL;
        $name = $row['prodname'];
        $this->loadClass('htmlheading', 'htmlelements');
        $headern = new htmlHeading();
        $headern->str = $this->objLanguage->languageText('mod_qrreview_reviewprod', 'qrreview').": ".$row['prodname'];
        $headern->type = 3;
        $ret .= $headern->show();
        
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'qrreview', 'Required').'</span>';
        
        // start the form
        $form = new form ('rev', $this->uri(array('action'=>'addreview'), 'qrreview'));
        
        $table = $this->newObject('htmltable', 'htmlelements');
        
        // product/service rating
        $this->loadClass('dropdown', 'htmlelements');
        $prodrate = new dropdown('prodrate');
        $prodrate->addOption();
        $prodrate->addOption(1, 1);
        $prodrate->addOption(2, 2);
        $prodrate->addOption(3, 3);
        $prodrate->addOption(4, 4);
        $prodrate->addOption(5, 5);
        $prodrate->addOption(6, 6);
        $prodrate->addOption(7, 7);
        $prodrate->addOption(8, 8);
        $prodrate->addOption(9, 9);
        $prodrate->addOption(10, 10);
        
        $table->startRow();
        //$prodrate = new textinput('prodrate');
        //$prodrate->size = 2;
        $prodrateLabel = new label($this->objLanguage->languageText('prodrate', 'qrreview').'&nbsp;', 'input_prodrate');
        $table->addCell($prodrateLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($prodrate->show()." out of 10");
        $table->endRow();
        
        // phone number
        $table->startRow();
        $phone = new textinput('phone');
        $phone->size = 10;
        $phoneLabel = new label($this->objLanguage->languageText('phone', 'qrreview').'&nbsp;', 'input_phone');
        $table->addCell($phoneLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($phone->show()." e.g. 0831234567");
        $table->endRow();
        
        // comment  box
        $this->loadClass('textarea', 'htmlelements');
        $table->startRow();
        $prodcomm = new textarea;
        $prodcomm->name = 'prodcomm';
        $prodcomm->value = '';
        $prodcomm->width ='50%';
        $prodcommLabel = new label($this->objLanguage->languageText('mod_qrreview_comment', 'qrreview').'&nbsp;', 'input_prodcomm');
        $table->addCell($prodcommLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($prodcomm->show());
        $table->endRow();
        
        // do the fieldset thang
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
        $fieldset->contents = $table->show();
        
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_qrreview_review", "qrreview"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();
        
        return $ret;
    }
    
}
?>
