<?php
/**
 *
 * aart helper class
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
 * @package   aart
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
 * sahriscollectionsman helper class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package aart
 *
 */
class ui extends object {

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
     * Constructor
     *
     * @access public
     */
    public function init() {
        $this->objLanguage    = $this->getObject('language', 'language');
        $this->objConfig      = $this->getObject('altconfig', 'config');
        $this->objSysConfig   = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->objUser        = $this->getObject('user', 'security');
    }
    
    public function aartform() {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'aart', 'Required').'</span>';
        $ret = NULL;
        // start the form
        $form = new form ('convert', $this->uri(array('action'=>'convert'), 'aart'));
        $table = $this->newObject('htmltable', 'htmlelements');
        
        // url
        $url = new textinput();
        $url->name = 'url';
        $url->width ='100%';
        $urlLabel = new label($this->objLanguage->languageText('mod_aart_url', 'aart').'&nbsp;', 'input_url');
        $table->addCell($urlLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($url->show()." ".$required);
        $table->endRow();
        
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText('mod_aart_enterurl', 'aart');
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_aart_convert", "aart"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show(); 
        
        return $ret;      
    }
    
}
?>

