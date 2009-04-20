<?php
/**
 * 
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
 * @package   ahis
 * @author    Isaac N. Oteyo <ioteyo@jkuat.ac.ke, isaacoteyo@gmail.com>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: add_animalmovement_tpl.php 12780 2009-03-11 10:46:10Z rosina $
 * @link      http://avoir.uwc.ac.za, http://www.jkuat.ac.ke
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('csslayout', 'htmlelements');
$this->loadClass('layer', 'htmlelements');

// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(3);
// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

$link = new link($this->uri(array('action' => 'default')));

$loadingIcon = $objIcon->show();

//title
//$title = $this->objLanguage->languageText('mod_movement_title', 'movement', 'Animal Movement');
$title = 'Livestock movement';

// Header
$header = new htmlheading();
$header->type = 2;
$header->str = $title;


/*if (isset($success)) {

    $objMsg = $this->getObject('timeoutmessage','htmlelements');
    $objMsg->setHideTypeToNone();
    switch ($success) {
        case 1:
            $objMsg->setMessage($this->objLanguage->languageText('mod_ahis_added', 'ahis')."<br />");
            break;
        case 2:
            $objMsg->setMessage($this->objLanguage->languageText('mod_ahis_deleted', 'ahis')."<br />");
            break;
        case 3:
            $objMsg->setMessage($this->objLanguage->languageText('mod_ahis_updated', 'ahis')."<br />");

            break;
        case 4:
            $objMsg->setMessage("<span class='error'>".$this->objLanguage->languageText('mod_ahis_duplicate', 'ahis')."</span><br />");
            $objMsg->setTimeOut('0');
            break;
    }

    $msg = $objMsg->show();

} 
else 
{
    $msg = 'Try adding the record again.';
}*/


$formTable = $this->newObject('htmltable', 'htmlelements');

$label_district = new label ('District: ', 'district');
$district = new textinput('district',$dist);
$district->extra = 'readonly';
//$district->extra = 'disabled';
//$district->size = 40;
$formTable->startRow();
//$formTable->cellpadding = 5;
$formTable->addCell($label_district->show(),NULL,NULL,'right');
$formTable->addCell($district->show(),NULL,NULL,'left');
$formTable->endRow();

// animal classification	
$label = new label ('Animal Classification: ', 'classification');
$classification = new dropdown('classification');
$classification->addFromDB($species, 'name', 'name'); 

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($classification->show(),NULL,NULL,'left');
$formTable->endRow();

$label_purpose = new label('Purpose: ', 'Purpose');
$radio_slaughter = new radio ('purpose');

$radio_slaughter->addOption('Slaughtered', 'Slaughtered');
$radio_rear = new radio ('purpose');
$radio_slaughter->addOption('Rearing', 'Rearing');

$formTable->startRow();
$formTable->addCell($label_purpose->show(),NULL,NULL,'right');
$formTable->addCell($radio_slaughter->show(),NULL,NULL,'left');

$formTable->startRow();
$formTable->addCell('',40);
$formTable->addCell($radio_rear->show(),40);
$formTable->endRow();

// animal origin	
$label = new label ('Animal origin: ', 'origin');
$origin = new textinput('origin');
//$origin->size = 40;

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($origin->show(),NULL,NULL,'left');
$formTable->endRow();

// animal destination	
$label = new label ('Animal destination: ', 'destination');
$destination = new textinput('destination');
//$destination->label = 'Must contain only letters of the alphabet.';
//$form->addRule('destination','Animal destination must contain letters of the alphabet only.', 'letteronly');
//$destination->size = 40;

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($destination->show(),NULL,NULL,'left');
$formTable->endRow();

$label_remarks = new label('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_remarks', 'ahis', 'Remarks: '), 'remarks');

$remarks = new textarea('remarks');
$formTable->startRow();
$formTable->addCell($label_remarks->show(), NULL,NULL,'right');
$formTable->addCell($remarks->show(),NULL,NULL,'left');
$formTable->endRow();

$formTable->startRow();
$formTable->addCell($error, NULL, NULL, NULL, NULL, "colspan=2");
$formTable->endRow();

$formAction = 'animalmovement_save';  
    $buttonText = 'Save';
	
	// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));

//form validations
$form->addRule('district', $this->objLanguage->languageText('mod_ahis_districterror','ahis'),'required');
$form->addRule('classification', $this->objLanguage->languageText('mod_ahis_classificationerror','ahis'),'required');
//$form->addRule('purpose', $this->objLanguage->languageText('mod_ahis_purposeerror','ahis'),'select');
$form->addRule('origin', $this->objLanguage->languageText('mod_ahis_originerror','ahis'),'letteronly');
$form->addRule('destination', $this->objLanguage->languageText('mod_ahis_destinationerror','ahis'),'letteronly');
//$form->addRule('destination','Animal destination must contain letters of the alphabet only.', 'letteronly');
$form->addRule('remarks', $this->objLanguage->languageText('mod_ahis_remarkserror', 'ahis'), 'required');

//container-table
$topTable = $this->newObject('htmltable', 'htmlelements');
 $topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();

$form->addToForm($topTable->show());
 
 $save = new button('animalmovement_save', 'Save');
 $save->setToSubmit();
 
 $cancel = new button('cancel','Cancel');
$cancel->setToSubmit();

$form->addToForm($save->show(),NULL,NULL,'right');
$form->addToForm($cancel->show());

$objLayer = new layer();
$objLayer->addToStr($header->show()."<hr />".$form->show());
$objLayer->align = 'center';

echo $objLayer->show(); 

?>
<style type="text/css">
	.labels
	{
		padding-left:100px;		
	}
	.inputtextboxes
	{
		padding-left:200;
	}
</style>