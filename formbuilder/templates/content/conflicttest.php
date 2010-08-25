<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/
//jquery-1.4.2.min.js', 'jquery'));
 $this->loadClass("textinput","htmlelements");
 $this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('switchmenu','htmlelements');
$this->loadClass('datepicker','htmlelements');
$hinput = new hiddeninput('hidden name','valeu hiddien');
$ti = new textinput('ti name', '$hinput','hidden');
echo $ti->show();
echo $hinput->show();


$switchmenu = $this->newObject('switchmenu', 'htmlelements');
 $switchmenu->addBlock('Title 1', 'Block Text 1 <br /> Block Text 1 <br /> Block Text 1');
 $switchmenu->addBlock('Title 2', 'Block Text 2 <br /> Block Text 2 <br /> Block Text 2', 'confirm'); // Adds
 //* a CSS Style
 $switchmenu->addBlock('Title 3', 'Block Text 3 <br /> Block Text 3 <br /> Block Text 3');

 echo $switchmenu->show();
//$objInputMasks = $this->getObject('inputmasks', 'htmlelements');
//echo $objInputMasks->show();

// $datePicker = $this->newObject('datepicker', 'htmlelements');
// $datePicker->name = 'storydate';
//$datePicker->setName("storydate");
//$datePicker->setDateFormat("Aug-06-1996");
// $datePicker->setDefaultDate("2010/02/02");
//echo $datePicker->show();
?>
