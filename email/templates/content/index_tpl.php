<?
    /**
    * Main index template for email module
    * The template builds a table structure showing new and old email
    * @author James Scoble
    * 
    */
    
    print $this->pagetitle();
    
    // Table object
    $objTableClass=&$this->newObject('htmltable','htmlelements');
    $objTableClass->width='100%';
    $objTableClass->attributes=" border=0 class='even'";
    $objTableClass->cellspacing='2';
    $objTableClass->cellpadding='2';
    
    // HTML links
    $readnew="<a href='".$this->uri(array('action'=>'list','userfolder'=>'new'))."'>";
    $readold="<a href='".$this->uri(array('action'=>'list','userfolder'=>'old'))."'>";
    $readtrash="<a href='".$this->uri(array('action'=>'list','userfolder'=>'trash'))."'>";
    $readall="<a href='".$this->uri(array('action'=>'showmail','userfolder'=>'mail'))."'>";
    
    // Table headings
    $objTableClass->addRow(array("&nbsp;"));
    $objTableClass->addRow(array("&nbsp;",$readall.$this->objLanguage->languageText('mod_email_all')."</a>",$email_count['all']));
    $objTableClass->addRow(array("&nbsp;"));
    $objTableClass->addRow(array("&nbsp;",$readnew.$this->objLanguage->languageText('mod_email_unread')."</a>",$email_count['new']));
    $objTableClass->addRow(array("&nbsp;"));
    $objTableClass->addRow(array("&nbsp;",$readold.$this->objLanguage->languageText('mod_email_mailbox')."</a>",$email_count['old']));
    $objTableClass->addRow(array("&nbsp;"));
    $objTableClass->addRow(array("&nbsp;",$readtrash.$this->objLanguage->languageText('word_trash')."</a>",$email_count['trash']));
    $objTableClass->addRow(array("&nbsp;"));
    $main= $objTableClass->show();
    
    $objTableFrame=$this->newObject('htmltable','htmlelements');
    $objTableFrame->width='100%';
    $objTableFrame->align='top';
    
    $objTableFrame->startRow();
    $objTableFrame->addCell("&nbsp;",null,"top",'left','odd','width="20%"');
    $objTableFrame->addCell($main,null,"top",NULL,NULL,NULL);
    $objTableFrame->addCell("&nbsp;",null,"top",'left','odd','width="20%"');
    $objTableFrame->endRow();
    
    print $this->linkLine();
    
    print $objTableFrame->show();

    print $this->linkLine();
?>
