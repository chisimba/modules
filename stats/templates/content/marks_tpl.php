<?php
/**
 * Stats tutorials on Chisimba
 * 
 * Marks template which shows the students their marks for each tutorial
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
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

$this->loadClass('textinput','htmlelements');

$objHead = $this->newObject('htmlheading', 'htmlelements');
$objHead->str = $objLanguage->languageText('mod_stats_tutmarks', 'stats');
$objHead->type = 2;

$sDetail = $this->newObject('htmlheading', 'htmlelements');
$sDetail->type = 3;
$sDetail->str = $this->objLanguage->languageText('mod_stats_marksfor','stats').": $student";

if (!$showAll) {
    $markLabel = $this->objLanguage->languageText('mod_stats_bestmark','stats');
    $toggleLink = $this->objLanguage->languageText("mod_stats_showall","stats");
    $showAll = 1;
} else {
    $markLabel = $this->objLanguage->languageText('mod_stats_mark','stats');
    $toggleLink = $this->objLanguage->languageText("mod_stats_showbest","stats");
    $showAll = 0;
}
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellpadding = $objTable->cellspacing = 2;
$head = array($this->objLanguage->languageText('mod_stats_testno','stats'),
              $this->objLanguage->languageText('mod_stats_testname','stats'),
              $markLabel,
              $this->objLanguage->languageText('mod_stats_timetaken','stats'));

$objTable->addHeader($head,null,"align='left'");

$class = "odd";
if (!empty($tutorials)) {
    foreach ($tutorials as $tut) {
        $objTable->startRow($class);
        $objTable->addCell($tut['testno']);
        $objTable->addCell($this->objTuts->testName[$tut['testno']]);
        $objTable->addCell("{$tut['best']}%");
        $objTable->addCell($tut['time']);
        $objTable->endRow();
        $class = ($class == "odd")? "even" : "odd";
    }
} else {
    $msg = $this->objLanguage->languageText('mod_stats_nomarks','stats');
    $objTable->startRow();
    $objTable->addCell("<span class='noRecordsMessage'>$msg</span>",null,"top",null,null,"colspan='4'");
    $objTable->endRow();
}

$link = $this->getObject('link','htmlelements');
$link->link($this->uri(array('action'=>$back)));
$link->link = $this->objLanguage->languageText("word_back");
$backLink = "<div style='float:right'>".$link->show()."</div>";

if ($this->objUser->isLecturer()) {
    $numberBox = new textinput('studentno');
    $submit = new button('go', $this->objLanguage->languageText("word_search"));
    $submit->setToSubmit();                           
    $formContent = $this->objLanguage->languageText("mod_stats_searchstudent",'stats').": ".$numberBox->show()." ".$submit->show();

    $objForm = $this->newObject('form','htmlelements');
    $objForm->action = $this->uri(array('action'=>'marks', 'back'=>$back));
    $objForm->addToForm($formContent);
    $form = "<br /><div style='float:left'>".$objForm->show()."</div>";
} else {
    $form = "";
}

$link->link($this->uri(array('action'=>'marks', 'studentno'=>$userName, 'back'=>$back, 'showAll'=>$showAll)));
$link->link = $toggleLink;
$form .= "<div style='float:right'>".$link->show()."</div>";
echo $objHead->show().$sDetail->show().$form.$objTable->show()."$backLink<br /><br />";
?>