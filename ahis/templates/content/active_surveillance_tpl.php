<?php
/**
 * ahis Active Survaillance new campaign screen Template
 *
 * Template for active surveillance new campaign capture screen
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
 * @author    Rosina Ntow <rntow@ug.edu.gh>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: active_surveillance_tpl.php 
 * @link      http://avoir.uwc.ac.za
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

$searchUri = $this->uri(array('action'=>'active_search'));
$objLink = new link($searchUri);
$objLink->link = $this->objLanguage->languageText('phrase_browsesurveillance');
$searchLink = '<p>'.$objLink->show() .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str =$this->objLanguage->languageText('phrase_active')." ".$this->objLanguage->languageText('phrase_newcampaign');

$objHeading->type = 2;



$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('layer','htmlelements');



$okButton = new button('ok', $this->objLanguage->languageText('word_next'));
$okButton->setToSubmit();
$cancelUri = $this->uri(array('action'=>'select_officer'));
$cancelButton = new button('cancel', $this->objLanguage->languageText('word_back'), "javascript: document.location='$cancelUri'");



$campNameBox = new textinput('campName', $campName);
//$officerDrop = new textinput('officerId',$officerId);

$officerDrop = new dropdown('officerId');
$officerDrop->addFromDB($userList, 'name', 'userid');
$officerDrop->setSelected($officerId);
$officerDrop->extra = 'disabled';
$diseaseDrop = new dropdown('disease');
$diseaseDrop->addFromDB($arrayoutbreak, 'name', 'id');
$diseaseDrop->setSelected($outbreakId);
$surveyTypeDrop = new dropdown('surveyType');
$surveyTypeDrop->addFromDB($arraysurveyType, 'name', 'id');
$surveyTypeDrop->setSelected($surveyTypeId);

$commentsBox = new textarea('comments', $comments , 4, 40);

$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
$objTable->cssClass = 'min50';


$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_campaign')." ".$this->objLanguage->languageText('word_name').": ");
$objTable->addCell($campNameBox->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','ahis').": $tab");
$objTable->addCell($officerDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_disease').": ");
$objTable->addCell($diseaseDrop->show());
$objTable->endRow();
$objTable->addCell($this->objLanguage->languageText('phrase_surveytype').": ");
$objTable->addCell($surveyTypeDrop->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_comments').": ");
$objTable->addCell($commentsBox->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('&nbsp');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($cancelButton->show());
$objTable->addCell($okButton->show(),NULL);//,'top','right');
$objTable->addCell('');
$objTable->endRow();


$this->loadClass('form','htmlelements');
$objForm = new form('reportForm', $this->uri(array('action' => 'active_addtest')));
$objForm->addToForm($objTable->show());
$objForm->addRule('campName', $this->objLanguage->languageText('mod_ahis_campnamereq', 'ahis'), 'required');


$objLayer = new layer();
$objLayer->addToStr($searchLink.$objHeading->show()."<hr />".$objForm->show());
$objLayer->align = 'center';


$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");


echo $objLayer->show();
?>