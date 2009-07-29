<?php
class giftops extends object {

    public function init() {
        // importing classes
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass("layer","htmlelements");
        $this->objLanguage = $this->getObject("language", "language");
    }

    public function displayForm($rname,$data) {
        // set up language items
        $dnlabel = $this->objLanguage->languageText('mod_addedit_donor','gift').":";
        $rnlabel = $this->objLanguage->languageText("mod_addedit_receiver","gift").":";
        $gnlabel = $this->objLanguage->languageText("mod_addedit_giftname","gift").":";
        $descriplabel = $this->objLanguage->languageText("mod_addedit_description","gift").":";
        $gvaluelabel = $this->objLanguage->languageText("mod_addedit_value","gift").":";
        $gstatelabel = $this->objLanguage->languageText("mod_addedit_state","gift").":";

        if(sizeof($data) == 0) {
            $objForm = new form('contactdetailsform', $this->uri(array('action' => 'submitAdd')));
        }
        else {
            $objForm = new form('contactdetailsform', $this->uri(array('action' => 'submitEdit')));
        }
		
        //Setting up input text boxes
        $objInputh1 = new textinput('dnvalue',$data['donor'], '', '15');
        $dnvalue = $objInputh1->show();
		
        $hiddenid = "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\" />";
			
        $objInputh2b = new textinput('gname',$data['giftname'], '', '15');
        $gnvalue = $objInputh2b->show();
			
        $objInputh3a = new textarea('descripvalue',$data['description'], 15, 50);
        $descripvalue = $objInputh3a->show();
			
        $objInputh3b = new textinput('gvalue',$data['value'], '', '15');
        $gvalue = $objInputh3b->show();
			
        $dropdown=&new dropdown('gstatevalue');
        $dropdown->addOption(1,$this->objLanguage->languageText("mod_dropdown_active","gift"));
        $dropdown->addOption(0,$this->objLanguage->languageText("mod_dropdown_notactive","gift"));
        
        if($data['listed'] == 1) {
            $dropdown->setSelected(1);
        }
        else {
            $dropdown->setSelected(0);
        }
        $gstatevalue = $dropdown->show();
		
        //Buttons OK and cancel
        $this->objSubmitButton=new button('Submit');
        $this->objSubmitButton->setValue($this->objLanguage->languageText("mod_addedit_btnSubmit","gift"));
        $this->objSubmitButton->setToSubmit();
			
        $this->objResetButton=new button('Reset');
        $this->objResetButton->setValue($this->objLanguage->languageText("mod_addedit_btnReset","gift"));
        $this->objResetButton->setToReset();
			
        $this->objCancelButton=new button('cancel');
        $this->objCancelButton->setValue($this->objLanguage->languageText("mod_addedit_btnCancel","gift"));
        $this->objCancelButton->setOnClick("window.location='".$this->uri(NULL)."';");
        
        //Defining table
        $objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->border='0';
        $objTable->startRow();
        $objTable->addCell($hiddenid, '', '', '', '', '');
        $objTable->addCell('', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($dnlabel, '', '', '', '', '');
        $objTable->addCell($dnvalue, '', '', '', '', '');
        $objTable->endRow();	
        $objTable->startRow();	
        $objTable->addCell($rnlabel, '', '', '', '', '');
        $objTable->addCell($rname, '', '', '', '', '');
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($gnlabel, '', '', '', '', '');
        $objTable->addCell($gnvalue, '', '', '', '', '');
        $objTable->addCell(" ", '', '', '', '', '');
        $objTable->endRow();
                        
        $objTable->startRow();
        $objTable->addCell($descriplabel, '', 'top', '', '', '');
        $objTable->addCell($descripvalue, '', '', '', '', 'colspan="3"');
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($gvaluelabel, '', '', '', '', '');
        $objTable->addCell($gvalue, '', '', '', '', '');
        $objTable->addCell(" ", '', '', '', '', '');
        $objTable->endRow();
                        
        $objTable->startRow();
        $objTable->addCell($gstatelabel, '', '', '', '', '');
        $objTable->addCell($gstatevalue, '', '', '', '', '');
        $objTable->endRow();
		
        $infoTable = $objTable->show();
			
        //Setting Up Form by adding all objects....
        $objForm->addRule("dnvalue",$this->objLanguage->languageText("mod_addedit_donorrequired","gift"),"required");
        $objForm->addRule("gname",$this->objLanguage->languageText("mod_addedit_giftnamerequired","gift"),"required");
        $objForm->addRule("descripvalue",$this->objLanguage->languageText("mod_addedit_descriptionrequired","gift"),"required");
        $objForm->addRule("gvalue",$this->objLanguage->languageText("mod_addedit_giftvaluerequired","gift"),"required");
        $objForm->addRule("gvalue",$this->objLanguage->languageText("mod_addedit_giftvaluenumeric","gift"),"numeric");

        $objForm->addToForm($infoTable);
			
        $objForm->addToForm('<br/> ');
        $objForm->addToForm($this->objSubmitButton);
        $objForm->addToForm($this->objResetButton);
        $objForm->addToForm($this->objCancelButton);
        $composeForm = $objForm->show();
			
        $pageData= $composeForm;
			
        //Defining Layer
        $objLayer = new layer();
        $objLayer->padding = '10px';
        $objLayer->str = $pageData;
        $pageLayer = $objLayer->show();
        return $pageLayer;
    }
function sendEmail($subject, $body) {
		
		$objSysconfig = $this->getObject('dbsysconfig','sysconfig');
		$adminemail = $objSysconfig->getValue('adminmail','gifts');
		$objMailer = $this->getObject('email', 'mail');
		$to = array($adminemail,'ana.m.ferreira@wits.ac.za');
		$objMailer->setValue('to', $to);
		$objMailer->setValue('from', 'noreply@wits.ac.za');
		$objMailer->setValue('subject', $subject);
		$objMailer->setValue('body', $body);
		$objMailer->send(FALSE);

	}
}
?>
