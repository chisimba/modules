<?php
class giftops extends object {

    /**
     * Initialises classes to be used
     */
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
        $this->loadClass("mouseoverpopup","htmlelements");
        $this->objLanguage = $this->getObject("language", "language");
    }

    /**
     * Builds the form for the addition of a new gift or editing an
     * existing gift from the database.
     * @param string $rname
     * @param array $data
     * @return string
     */
    public function displayForm($data,$action) {
        $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
        $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
        $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
        $this->appendArrayVar('headerParams', $extbase);
        $this->appendArrayVar('headerParams', $extalljs);
        $this->appendArrayVar('headerParams', $extallcss);

        // set up language items
        $dnlabel = $this->objLanguage->languageText('mod_addedit_donor','gift').":";
        $rnlabel = $this->objLanguage->languageText("mod_addedit_receiver","gift").":";
        $gnlabel = $this->objLanguage->languageText("mod_addedit_giftname","gift").":";
        $descriplabel = $this->objLanguage->languageText("mod_addedit_description","gift").":";
        $gvaluelabel = $this->objLanguage->languageText("mod_addedit_value","gift").":";

        if(sizeof($data) == 0) {
            $objForm = new form('contactdetailsform', $this->uri(array('action' => 'submitAdd')));
        }
        else {
            $objForm = new form('contactdetailsform', $this->uri(array('action' => 'submitEdit','id' => $data['id'])));
        }

        $mainjs = "Ext.onReady(function(){
            new Ext.ToolTip({
            target: 'donortip',
            html: '".$this->objLanguage->languageText('mod_add_donortip','gift')."',
            width: 200
            })

            new Ext.ToolTip({
            target: 'giftnametip',
            html: '".$this->objLanguage->languageText('mod_add_giftnametip','gift')."',
            width: 200
            })

            new Ext.ToolTip({
            target: 'descriptiontip',
            html: '".$this->objLanguage->languageText('mod_add_descriptiontip','gift')."',
            width: 200
            })

            new Ext.ToolTip({
            target: 'valuetip',
            html: '".$this->objLanguage->languageText('mod_add_valuetip','gift')."',
            width: 200
            })

            Ext.QuickTips.init();

        });";

        //Setting up input text boxes
        $objInputh1 = new textinput('dnvalue',$data['donor'], '', '74');
        $dnvalue = $objInputh1->show()."<br><br>";
		
        $hiddenid = "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\" />";
			
        $objInputh2b = new textinput('gname',$data['giftname'], '', '74');
        $gnvalue = $objInputh2b->show()."<br><br>";
			
        $objInputh3a = new textarea('descripvalue',$data['description'], 15, 55);
        $descripvalue = $objInputh3a->show()."<br><br>";
			
        $objInputh3b = new textinput('gvalue',$data['value'], '', '30');
        $gvalue = $objInputh3b->show()."<br><br>";
			
        //Buttons OK and cancel
        $this->objSubmitButton=new button('Submit');
        $this->objSubmitButton->setValue($this->objLanguage->languageText("mod_addedit_btnSave","gift"));
        $this->objSubmitButton->setToSubmit();
			
        $this->objResetButton=new button('Reset');
        $this->objResetButton->setValue($this->objLanguage->languageText("mod_addedit_btnReset","gift"));
        $this->objResetButton->setToReset();
			
        $this->objCancelButton=new button('cancel');
        $this->objCancelButton->setValue($this->objLanguage->languageText("mod_addedit_btnCancel","gift"));
        
        if($action == 'add')
            $this->objCancelButton->setOnClick("window.location='".$this->uri(NULL)."';");
        else
            $this->objCancelButton->setOnClick("window.location='".$this->uri(array('action'=>'result'))."';");
        
        //Defining table
        $objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->border='0';

        $width = 100;
        $valign = 'top';
        $objTable->startRow();
        $objTable->addCell($dnlabel);
        $objTable->addCell($dnvalue);
        $objTable->addCell('<div id="donortip">[?]</div>', $width, 'top', 'right');
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($gnlabel);
        $objTable->addCell($gnvalue);
        $objTable->addCell('<div id="giftnametip">[?]</div>', $width, 'top', 'right');
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($descriplabel, '', 'top');
        $objTable->addCell($descripvalue, '', '', '', '', 'colspan="3"');
        $objTable->addCell('<div id="descriptiontip">[?]</div>',$width, NULL, 'left');
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($gvaluelabel);
        $objTable->addCell($gvalue);
        $objTable->addCell('<div id="valuetip">[?]</div>', $width, 'top', 'right');
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
        $pageLayer = $objLayer->show().'<script type="text/javascript">'.$mainjs.'</script>';

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
