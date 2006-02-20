<?
    /**
    * Template for email main page
    * @author James Scoble
    */
    print $this->getJavascriptFile('selectall.js');
    
    // table object for icons and links
    $objTableClass=$this->newObject('htmltable','htmlelements');
    $objTableClass->width='99%';
    $objTableClass->attributes=" border=0";
    $objTableClass->cellspacing='1';
    $objTableClass->cellpadding='2';

    $objIcons=$this->newObject('geticon','htmlelements');
    $objIcons->setIcon('notes');
    $objIcons->alt=$this->objLanguage->languageText('word_new');
    $newIcon=$objIcons->show();
    
    $new="<a href='".$this->uri(array('action'=>'new'))."' class='pseudobutton'>".$newIcon."</a>";

    $action=$this->getParam('action');
    $userfolder=$this->getParam('userfolder');
    $titleline=NULL;
    if ($userfolder=='trash'){
        $titleline='mod_email_read_trash';
    }
    if (isset($this->emailsendtext)){
        $titleline=$this->emailsendtext;
    }
    $status=$this->getParam('status');
    if ($status=='emailsent'){
        $titleline='mod_email_message_sent';
    }

    $objIcons=&$this->newObject('geticon','htmlelements');
    $objIcons->setIcon('unreadletter');
    $objIcons->alt=$this->objLanguage->languageText('word_new');
    $newEmailIcon=$objIcons->show();
    
    $objIcons=&$this->newObject('geticon','htmlelements');
    $objIcons->setIcon('readletter');
    $objIcons->alt=$this->objLanguage->languageText('word_old');
    $oldEmailIcon=$objIcons->show();
    
    // Now the main code block is run below. If there are emails to display, a table is built up to show them.
    // If not, a message to that effect is output instead. 
    // $emailList is an associative array passed from the controller, containing all the email display details 
    if (isset($emailList)) {
        $count=count($emailList);

        // $senderlink, $datelink and $subjectlink are href links for sorting the emails by sender, date or subject
        //Code block for making sorting reversible - added 15 March 2005
        $messageOrder['sender']='sender';
        $messageOrder['date']='date';
        $messageOrder['subject']='subject';
        $oldOrder=$this->getParam('messageorder','none');
        $messageOrder[$oldOrder]='reverse'.$oldOrder;
        
        $senderlink="<a href='".$this->uri(array('action'=>$action,'userfolder'=>$userfolder,'messageorder'=>$messageOrder['sender']))."'>".$this->objLanguage->LanguageText('word_from','From')."</a>";
        $datelink="<a href='".$this->uri(array('action'=>$action,'userfolder'=>$userfolder,'messageorder'=>$messageOrder['date']))."'>".$this->objLanguage->LanguageText('word_date','Date')."</a>";
        $subjectlink="<a href='".$this->uri(array('action'=>$action,'userfolder'=>$userfolder,'messageorder'=>$messageOrder['subject']))."'>".$this->objLanguage->LanguageText('word_subject','Subject')."</a>"; 
        $row=array('&nbsp;','&nbsp;',$subjectlink,$senderlink,$datelink);
        $word_read=$this->objLanguage->LanguageText('word_read','Read');
        $word_delete=$this->objLanguage->LanguageText('word_delete','Delete');
        $objTableClass->addHeader($row);
            
        $objIcons->setIcon('delete');
        $objIcons->alt=$word_delete;
        $deleteIcon=$objIcons->show();
        $objIcons->setIcon('edit_sm');
        $objIcons->alt=$word_read;
        $readIcon=$objIcons->show();
        
        if ($count>0) {
            // Now the main loop for showing the email messages
            foreach ($emailList as $line)
            {
                if ($line['date_read']==0){
                    $boldTag='<b>';
                    $boldClose='</b>';
                    $objTableClass->trClass='emailNew';
                    $emailIcon=$newEmailIcon;
                } else {
                    $boldTag='';
                    $boldClose='';
                    $objTableClass->trClass='emailOld';
                    $emailIcon=$oldEmailIcon;
                }
                $readLink="<a href=\"".$this->uri(array('action'=>'read','emailId'=>$line['email_id']))."\" class='".$objTableClass->trClass."'>";
                $objTableClass->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$objTableClass->trClass."'; \"";
                $objTableClass->startRow();
                $objTableClass->addCell("<input type=checkbox name='delete[]' value='".$line['email_id']."'>","20", NULL, NULL, NULL,"");
                $objTableClass->addCell($emailIcon,"", NULL, NULL, NULL,"");
                $objTableClass->addCell($readLink.$boldTag.$line['subject'].$boldClose."</a>","50%", NULL, NULL, NULL,"");
                $objTableClass->addCell("<nobr>".$readLink.$boldTag.$line['fullname'].$boldClose."</a></nobr>","", NULL, NULL, NULL,"");
                $objTableClass->addCell("<nobr>".$readLink.date("j M Y - H:i",strtotime($line['date']))."</a></nobr>","150", NULL, NULL, NULL,"");

                $read="<a href=\"".$this->uri(array('action'=>'read','emailId'=>$line['email_id']))."\" class='pseudobutton'>".$readIcon."</a>";
                $delete="<a href=\"".$this->uri(array('action'=>'delete','emailId'=>$line['email_id'],'userfolder'=>$this->userfolder,'nextaction'=>$action,'nextfolder'=>$userfolder))."\" class='pseudobutton'>".$deleteIcon."</a>";
                $objTableClass->endRow();
            }
        }
        if ($count==0){
            $noMail="<span class='noRecordsMessage'>".$this->objLanguage->LanguageText('mod_email_no_email')."</span>\n";
            $objTableClass->startRow();
            $objTableClass->addCell($noMail,NULL,NULL,NULL,NULL,'colspan=5');
            $objTableClass->endRow();
        }

    }
    // Finally here we output this table in a string.
    $main= $objTableClass->show();

    // Building a form, for the 'delete selected' button at the bottom to work.
    // The whole display table must go into this form, since each email shown must have a clickable link
    $this->loadclass('form','htmlelements');
    $newform= new form('DeleteSelected',$this->uri(array('action'=>'deleteselected','nextaction'=>$action,'nextfolder'=>$userfolder)));
    $newform->displayType=3;
    
    // Paging is not being used, so having "page 1 of 3" type messages serves no function for now.
    //if ($count!=0){
    //    $newform->addToForm(str_replace('{NUM3}',$count,str_replace('{NUM2}',$count,str_replace('{NUM1}','1',$this->objLanguage->languageText('mod_email_count')))));
    //}
    $newform->addToForm($objTableClass->show()); //print $objTableFrame->show();

    // Display the button to delete selected emails only if there are more than zero emails
    if ($count!=0){
        $deletebutton="&nbsp;&nbsp;<input type=submit name='deleteSelected' class='button' value='".$this->objLanguage->languageText('mod_email_deleteselected')."'>"; 

        $deletebutton=$this->newObject('button','htmlelements');
        $deletebutton->button('deleteSelected',$this->objLanguage->languageText('mod_email_deleteselected'));
        $deletebutton->setToSubmit();
        
        $selectbutton=$this->newObject('button','htmlelements');
        $selectbutton->setOnClick("javascript:SetAllCheckBoxes('DeleteSelected', 'delete[]', true);");
        $selectbutton->setValue($this->objLanguage->languageText('mod_email_selectall'));
        
        $unselectbutton=$this->newObject('button','htmlelements');
        $unselectbutton->setOnClick("javascript:SetAllCheckBoxes('DeleteSelected', 'delete[]', false);");
        $unselectbutton->setValue($this->objLanguage->languageText('mod_email_selectnone'));

        $newform->addToForm($selectbutton);
        $newform->addToForm("&nbsp;");
        $newform->addToForm($unselectbutton);
        $newform->addToForm("&nbsp;");
        $newform->addToForm($deletebutton);
    }
    $main=$newform->show(); //print $newform->show();
    
    $objTableFrame=$this->newObject('htmltable','htmlelements');
    $objTableFrame->width='99%';
    $objTableFrame->align='top';

    if ($count>10){
        $objTableFrame->startRow();
        $objTableFrame->addCell($this->linkLine(),NULL,'bottom',NULL,'',NULL);
        $objTableFrame->endRow();
    }
    
    $objTableFrame->startRow();
    $objTableFrame->addCell($main,NULL,"top",NULL,'',NULL);
    $objTableFrame->endRow();
    
    $objTableFrame->startRow();
    $objTableFrame->addCell($this->linkLine(),NULL,'bottom',NULL,'',NULL);
    $objTableFrame->endRow();
    
    print $this->pagetitle($titleline);
    print $objTableFrame->show();
    
?>
