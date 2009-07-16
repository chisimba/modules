<?php

// add javascript for the hour calculations
$jquery = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/jquery-1.2.6.min.js').'" type="text/javascript"></script>';
$calcjs = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/calc.js').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $jquery);
$this->appendArrayVar('headerParams', $calcjs);

$this->loadClass('form','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('htmlheading','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('checkbox','htmlelements');
$this->loadClass('htmltable','htmlelements');

$form = new form("formd",$this->submitAction);

$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleD','ads') ."<sup>1</sup>", 2);
//$heading = new htmlheading($this->objLanguage->languageText('mod_formD_Heading','ads')."<sup>1</sup><hr />");

// Questions
$D1 = new textinput("D1",$this->formValue->getValue('D1'),"text","20");

$text1 = new textarea('D2_1',$this->formValue->getValue('D2_1'),15,25);
$text2 = new textarea('D2_2',$this->formValue->getValue('D2_2'),15,25);
$text3 = new textarea('D2_3',$this->formValue->getValue('D2_3'),15,25);

$D2 = new htmltable();
$D2->width = "90";
$D2->startRow();
$D2->addCell($this->objLanguage->languageText('mod_formD_D2_1','ads'));
$D2->addCell($this->objLanguage->languageText('mod_formD_D2_2','ads'));
$D2->addCell($this->objLanguage->languageText('mod_formD_D2_3','ads'));
$D2->endRow();
$D2->startRow();
$D2->addCell($text1->show()."<br>".$this->formError->getError('D2_1'));
$D2->addCell($text2->show()."<br>".$this->formError->getError('D2_2'));
$D2->addCell($text3->show()."<br>".$this->formError->getError('D2_3'));
$D2->endRow();
$D3 = new textarea('D3',$this->formValue->getValue("D3"),5,50);
$check1 = new checkbox('D4_1', NULL, ($this->formValue->getValue('D4_1') == "true" ? true : false));
$check1->setValue('true');
$check2 = new checkbox('D4_2', NULL, ($this->formValue->getValue('D4_2') == "true" ? true : false));
$check2->setValue('true');
$check3 = new checkbox('D4_3', NULL, ($this->formValue->getValue('D4_3') == "true" ? true : false));
$check3->setValue('true');
$check4 = new checkbox('D4_4', NULL, ($this->formValue->getValue('D4_4') == "true" ? true : false));
$check4->setValue('true');
$check5 = new checkbox('D4_5', NULL, ($this->formValue->getValue('D4_5') == "true" ? true : false));
$check5->setValue('true');
$check6 = new checkbox('D4_6', NULL, ($this->formValue->getValue('D4_6') == "true" ? true : false));
$check6->setValue('true');
$check7 = new checkbox('D4_7', NULL, ($this->formValue->getValue('D4_7') == "true" ? true : false));
$check7->setValue('true');
$check8 = new checkbox('D4_8', NULL, ($this->formValue->getValue('D4_8') == "true" ? true : false));
$check8->setValue('true');
$D4 =  "<table border=\"1px\" cellspacing=\"0px\">
	<tr><td colspan=\"2\" align=\"center\">".$this->objLanguage->languageText('mod_formD_D4_Heading','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check1->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_1','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check2->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_2','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check3->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_3','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check4->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_4','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check5->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_5','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check6->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_6','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check7->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_7','ads')."</td></tr>
	<tr><td class=\"padleft\">".$check8->show()."</td><td>".$this->objLanguage->languageText('mod_formD_D4_8','ads')."
	<ul>
	<li>".$this->objLanguage->languageText('mod_formD_D4_8B1','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D4_8B2','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D4_8B3','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D4_8B4','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D4_8B5','ads')."</li>
	</ul>
	</td></tr>
	</table>
	";
$style = '<style type="text/css">
	  .padleft{
		width : 50px;
	        text-align : center;
	}
	</style>
	';
echo $style;
$input1 = new textinput('D5_1',$this->formValue->getValue('D5_1'),'number','3');
$input2 = new textinput('D5_2',$this->formValue->getValue('D5_2'),'number','3');
$input3 = new textinput('D5_3',$this->formValue->getValue('D5_3'),'number','3');
$input4 = new textinput('D5_4',$this->formValue->getValue('D5_4'),'number','3');
$input5 = new textinput('D5_5',$this->formValue->getValue('D5_5'),'number','3');
$input6 = new textinput('D5_6',$this->formValue->getValue('D5_6'),'number','3');
$input7 = new textinput('D5_7',$this->formValue->getValue('D5_7'),'number','3');
$input8 = new textinput('D5_8',$this->formValue->getValue('D5_8'),'number','3');
$input9 = new textinput('D5_9',$this->formValue->getValue('D5_9'),'number','3');

$D5 = $this->objLanguage->languageText('mod_formD_D5_S','ads')."
	<ul>
	<li>".$this->objLanguage->languageText('mod_formD_D5_SB1','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D5_SB2','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D5_SB3','ads')."</li>
	<li>".$this->objLanguage->languageText('mod_formD_D5_SB4','ads')."</li>
	</ul>
	<br><br>
	<table border=\"1px\" cellspacing=\"0px\">
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5a','ads')."<br>".$this->formError->getError('D5a')."</td>
	<td>".$input1->show()."</td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5b','ads')."<br>".$this->formError->getError('D5b')."</td>
	<td>".$input2->show()."</td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5c','ads')."<br>".$this->formError->getError('D5c')."</td>
	<td>".$input3->show()."</td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5d','ads')."<br>".$this->formError->getError('D5d')."</td>
	<td>".$input4->show()."</td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5e','ads')."<br>".$this->formError->getError('D5e')."</td>
	<td>".$input5->show()."</td>
	</tr>
	<tr>
	<td><b>".$this->objLanguage->languageText('mod_formD_D5_1','ads')."</b></td>
	<td id=\"totContTime\" style=\"text-align:right;padding:0 0.8em;\"></td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5f','ads')."<br>".$this->formError->getError('D5f')."</td>
	<td>".$input6->show()."</td>
	</tr>
	<tr>
	<td><b>".$this->objLanguage->languageText('mod_formD_D5_2','ads')."</b></td>
	<td id=\"totNotTime\" style=\"text-align:right;padding:0 0.8em;\"></td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5g','ads')."<br>".$this->formError->getError('D5g')."</td>
	<td>".$input7->show()."</td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5h','ads')."<br>".$this->formError->getError('D5h')."</td>
	<td>".$input8->show()."</td>
	</tr>
	<tr>
	<td><b>".$this->objLanguage->languageText('mod_formD_D5_3','ads')."</b></td>
	<td id=\"totExamTime\" style=\"text-align:right;padding:0 0.8em;\"></td>
	</tr>
	<tr>
	<td>".$this->objLanguage->languageText('mod_formD_D5i','ads')."<br>".$this->formError->getError('D5i')."</td>
	<td>".$input9->show()."</td>
	</tr>
	<tr>
	<td><b>".$this->objLanguage->languageText('mod_formD_D5_4','ads')."</b></td>
	<td id=\"totNotTime2\" style=\"text-align:right;padding:0 0.8em;\"></td>
	</tr>
	<tr>
	<td><b>".$this->objLanguage->languageText('mod_formD_D5_5','ads')."</b></td>
	<td></td>
	</tr>
	</table>
	";
$D5 .= "<br />";
$errorcodes = array("D5_1", "D5_2","D5_3", "D5_4","D5_5", "D5_6","D5_7", "D5_8","D5_9");
$count = 1;
foreach ($errorcodes as $error) {
  if ($this->formError->getError($error) != "") {
    $this->formError->setError($error, "Field $count: " . $this->formError->errorarray[$error]);
    $D5 .= $this->formError->getError($error) . "<br />";
  }
  $count++;
}
$D6 = new textarea('D6',$this->formValue->getValue('D6'),5,25);
$D7 = new textarea('D7',$this->formValue->getValue('D7'),5,25);
$submit = new button('submit','Next'); //originally submit
$submit->setToSubmit();
// done

$form->addToForm($header->show(). "<br />");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D1','ads')."</b><br>".$D1->show()."<br>".$this->formError->getError('D1')."<br>");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D2','ads')."</b><br><br>".$D2->show()."<br>".$this->formError->getError('D2')."<br>");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D3','ads')."</b><br>".$D3->show()."<br>".$this->formError->getError('D3')."<br>");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D4','ads')."</b>".$D4."<br><br>");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D5','ads')."</b><br><br>".$D5."<br>".$this->formError->getError('D5')."<br>");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D6','ads')."</b><br>".$D6->show()."<br>".$this->formError->getError('D6')."<br>");
$form->addToForm("<b>".$this->objLanguage->languageText('mod_formD_D7','ads')."</b><br>".$D7->show()."<br>".$this->formError->getError('D7')."<br>");
$form->addToForm($submit->show());


/*===================================
Don't know what $coursedata is
=====================================
*/

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'));
$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $form->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
