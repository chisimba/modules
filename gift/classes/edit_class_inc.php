<?php
class edit extends object {

    public function init() {
        $this->loadClass('link','htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('htmlheading','htmlelements');
        $this->objLanguage = $this->getObject("language", "language");
    }

    public function getResults($data) {

        $title = new htmlheading($this->objLanguage->languageText('mod_edit_pickdonation','gift'),2);

        $table = new htmltable();
        $table->startRow();
        $table->addHeaderCell($this->objLanguage->languageText('mod_addedit_donor','gift'));
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_receiver","gift"));
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_giftname","gift"));
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_description","gift"));
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_value","gift"));
        $table->addHeaderCell($this->objLanguage->languageText("mod_addedit_state","gift"));
        $table->endRow();

        $i = 0;
        foreach ($data as $info) {
            $donor = $info['donor'];
            $recipient = $info['recipient'];
            $giftname = $info['giftname'];
            $description = $info['description'];
            $value = $info['value'];
            $listed = $info['listed'];
            $link   = new link($this->uri(array("linknumber"=>"$i","action"=>"edit")));
            $link->link = $this->objLanguage->languageText("mod_linkedit_edit","gift");
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
            $table->addCell($link->show());
            $table->endRow();
        }
        if (sizeof($data)==0) {
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('mod_edit_NoResults','gift'),'','','','','colspan="6"');
            $table->endRow();
        }

        $table = $title->show().$table->show();
        return $table;
    }
}
?>
