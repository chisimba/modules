<?php
class edit extends object {

    public function init() {
        $this->loadClass('link','htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('htmlheading','htmlelements');
        $this->objLanguage = $this->getObject("language", "language");
    }

    public function getResults($data) {

        $this->objCancelButton=new button('cancel');
        $this->objCancelButton->setValue($this->objLanguage->languageText("mod_addedit_btnCancel","gift"));
        $this->objCancelButton->setOnClick("window.location='".$this->uri(NULL)."';");

        $title = new htmlheading($this->objLanguage->languageText('mod_edit_pickdonation','gift'),2);

        $table = new htmltable();
        $table->cellspacing = 5;
        $table->startRow();
        $table->addHeaderCell($this->objLanguage->languageText('mod_addedit_donor','gift'),'10%');
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_receiver","gift"),'10%');
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_giftname","gift"),'10%');
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_description","gift"),'50%');
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_value","gift"),'10%');
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_state","gift"),'10%');
        $table->endRow();

        $i = 0;
        foreach ($data as $info) {
            $donor = $info['donor'];
            $recipient = $info['recipient'];
            $giftname = $info['giftname'];
            $description = $info['description'];
            $value = $info['value'];
            $listed = $info['listed'];
            $editLink   = new link($this->uri(array("action"=>"edit","id"=>$info['id'])));
            $editLink->link = $this->objLanguage->languageText("mod_linkedit_edit","gift");
            $archiveLink   = new link($this->uri(array("action"=>"archive","id"=>$info['id'])));
            $archiveLink->link = $this->objLanguage->languageText("mod_linkedit_archive","gift");
            $i++;
	
            if ($listed) {
                $listed = $this->objLanguage->languageText("mod_dropdown_active","gift");
            }
            else {
                $listed = $this->objLanguage->languageText("mod_dropdown_notactive","gift");
            }
	
            $table->startRow();
            $table->addCell($donor);
            $table->addCell($recipient);
            $table->addCell($giftname);
            $table->addCell($description);
            $table->addCell($value);
            $table->addCell($listed);
            $table->addCell($editLink->show());
            $table->addCell($archiveLink->show());
            $table->endRow();
        }
        if (sizeof($data)==0) {
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_edit_NoResults','gift'),'','','','','colspan="6"');
            $table->endRow();
        }

        $table->startRow();
        $table->addCell("<br>".$this->objCancelButton->show());
        $table->endRow();

        $table = $title->show().$table->show();
        return $table;
    }
}
?>
