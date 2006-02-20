<?
    /**
    * Template file for reading email
    * @author Jame Scoble
    */
    $pagetitle=$this->pagetitle();

    // Table object
    $objTableClass=$this->newObject('htmltable','htmlelements');
    $objTableClass->width='99%';
    $objTableClass->attributes=" border=0";
    $objTableClass->cellspacing='5';
    $objTableClass->cellpadding='5';


    $row=array('From',$email['from']);
    $email=$this->getVar('email');

    // From
    $objTableClass->startRow();
    $objTableClass->addCell('<b>'.$this->objLanguage->LanguageText('word_from','From').'</b>',"60",NULL,NULL,NULL);
    $objTableClass->addCell($email['name'],"", NULL, NULL, NULL,"class='emailInfo' colspan=5");
    $objTableClass->endRow();

    // Subject
    $objTableClass->startRow();
    $objTableClass->addCell('<b>'.$this->objLanguage->LanguageText('word_subject','Subject').'</b>',"60",NULL,NULL,NULL);
    $objTableClass->addCell($email['subject'],"", NULL, NULL, NULL,"class='emailInfo' colspan=5");
    $objTableClass->endRow();

    // Date Sent
    $objTableClass->startRow();
    $objTableClass->addCell('<b>'.$this->objLanguage->LanguageText('mod_email_datesent','Sent').'</b>',"60",NULL,NULL,NULL);
    $objTableClass->addCell(date("j M Y - H:i",strtotime($email['date_sent'])),"", NULL, NULL, NULL,"class='emailInfo' colspan=5");
    $objTableClass->endRow();

    // Email message body
    $objTableClass->startRow();
    $objTableClass->addCell($email['message'],"", NULL, NULL, NULL,"class='even' colspan=6");
    $objTableClass->endRow();

    // Attached files 
    if (count($email['attach'])>0){
        $attachments='';
        foreach ($email['attach'] as $line)
        {
            $link=$this->uri(array('action'=>'filedownload','fileId'=>$line['fileId']));
            $text=$line['name'];
            $filesize=ceil($line['size']/100).'K';
            $attachments.="<a href='$link'>$text&nbsp;($filesize)</a>&nbsp;  ";
        }
        $objTableClass->startRow();
        $objTableClass->addCell('<b>'.$this->objLanguage->LanguageText('mod_email_attach2','Attachments').'</b>',"60",NULL,NULL,NULL);
        $objTableClass->addCell($attachments,"", NULL, NULL, NULL,"colspan=6 class='emailInfo'");
        $objTableClass->endRow();
            
    }
    
    // Action links - reply or delete
    $reply='<a href="'.$this->uri(array('action'=>'reply','emailId'=>$email['email_id'])).'" class="pseudobutton" >'.$this->objLanguage->LanguageText('word_reply','Reply').'</a>';
    if ($email['from']==FALSE){
        $reply='';
    }
    $delete='<a href="'.$this->uri(array('action'=>'delete','emailId'=>$email['email_id'])).'" class="pseudobutton" >'.$this->objLanguage->LanguageText('word_delete','Delete').'</a>';


    // Previous/Next searching
    // We check to see if there IS a 'previous' or 'next' email
    // And only display the link if such an email exists.
    if ($email['folder']=='trash'){
        $lookfolder='trash';
    } else {
        $lookfolder='mail'; // So that we detect both "new" and "old" - just not "trash"
    }
    // Call the function in the kngmail class here
    $previousEmail=$this->objMail->getPrevious($email['email_id'],$this->userId,$email['date_sent'],$lookfolder);
    if ($previousEmail!=$email['email_id']){
        $previous='<a href="'.$this->uri(array('action'=>'readprevious','emailId'=>$email['email_id'],'dateInfo'=>$email['date_sent'])).'" class="pseudobutton" >'.$this->objLanguage->LanguageText('word_previous','Previous').'</a>';
    } else {
        $previous='';
    }
    // Same again for the next email
    $nextEmail=$this->objMail->getNext($email['email_id'],$this->userId,$email['date_sent'],$lookfolder);
    if ($nextEmail!=$email['email_id']){
        $next='<a href="'.$this->uri(array('action'=>'readnext','emailId'=>$email['email_id'],'dateInfo'=>$email['date_sent'])).'" class="pseudobutton" >'.$this->objLanguage->LanguageText('word_next','Next').'</a>';
    } else {
        $next='';
    }



    // Output code
    $objTableClass->startRow();
    $objTableClass->addCell($previous,"", NULL, NULL, NULL,"align='left'");
    $objTableClass->addCell($delete."&nbsp;/&nbsp;".$reply,"", NULL, NULL, NULL," align='center' colspan=4");
    $objTableClass->addCell($next,"", NULL, NULL, NULL," align='right'");
    $objTableClass->endRow();

    $main= $objTableClass->show();
                                                                                                                                             
    // HTML table to hold all the other tables
    $objTableFrame=$this->newObject('htmltable','htmlelements');
    $objTableFrame->width='99%';
    $objTableFrame->align='top';
    
    $objTableFrame->startRow();
    $objTableFrame->addCell($pagetitle,NULL,"top");
    $objTableFrame->endRow();
    
    $objTableFrame->addRow(array($main));
    $objTableFrame->addRow(array($this->linkLine()));
 
    print $objTableFrame->show();

?>
