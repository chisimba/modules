<?
    /*
    * Email editing template for email module
    * This template has a main table/form for the message, and an iframe sidebar for attachments and the email addressbook
    * @author James Scoble
    * @licence GPL
    */
    $objIcons=$this->newObject('geticon','htmlelements');
    $objIcons->setIcon('email1');
    $objIcons->alt=$this->objLanguage->languageText('word_send');
    $sendIcon=$objIcons->show();
    
    $objTableClass=$this->newObject('htmltable','htmlelements');
    $objTableClass->width='99%';
    $objTableClass->attributes=" border='0'";
    $objTableClass->cellspacing='5';
    $objTableClass->cellpadding='5';

    // If the message being composed is a reply, the 'To' field will have the address of the person being replied to.
    if (isset($email['from']))
    {
        $to_value=$email['from'];
    }
    else
    {
        $to_value='';
        $recipient=$this->getParam('recipient');
        if ($recipient){
            $to_value=$this->objMail->lookupUser($recipient,'fullname').' <'.$this->objMail->lookupUser($recipient,'username').'>';
        }
    }
    
    $emailId=$this->userId.time();
    
    $this->loadClass('textinput','htmlelements');
   
   // The page title goes in here
    $objTableClass->startRow();
    $objTableClass->addCell($this->pagetitle('mod_email_compose'),"", NULL, NULL, NULL," colspan=2");
    $objTableClass->endRow();

    $input=new textinput('to',$to_value);
    $input->size='40';
    $row=array($this->objLanguage->LanguageText('word_to','To'),$input->show());
    $objTableClass->addRow($row);

    if (isset($email['subject']))
    {
        $subject=$email['subject'];
    }
    else
    {
        $subject='';
    }
    $input= new textinput('subject',$subject);
    $input->size='40';
    $row=array($this->objLanguage->LanguageText('word_subject','Subject'),$input->show());
    $objTableClass->addRow($row);

    // Here we set the actual message area
    $this->loadclass('textarea','htmlelements');
    $textarea= new textarea('messagetext',NULL,'20','55');
    $objTableClass->startRow();
    $objTableClass->addCell($textarea->show(),"", NULL, NULL, NULL," colspan=2");
    $objTableClass->endRow();

    $row=array('',"<input type=submit class='button' value='".$this->objLanguage->LanguageText('word_send','Send')."'>");
    $objTableClass->addrow($row);


    $this->loadClass('form','htmlelements');
    $email1= new form('Email1',$this->uri(array('action'=>'sendnew')));
    $email1->setDisplayType(3);
    

    $email1->addToForm($objTableClass->show()."\n<input type='hidden' name='emailId' value='$emailId'>\n");
    $form5=$email1->show();

    // The IFRAME will be here
    //Jonathan quick fix : height=99% does not seem to work used pixel height instead..
    $form4="<h4>".$this->objLanguage->languageText('mod_email_addrbook')."</h4>\n";
    $form4.=$this->emailUserMenu()."<br />\n";
    $form4.="<iframe width='99%' height='150' name='userlist' ID='userlist'  src='".$this->uri(array('action'=>'userlist','userfield'=>'A'))."'></iframe>";
    $form4.="<h4>".$this->objLanguage->languageText("mod_email_attach1")."</h4>\n";
    $form4.="<iframe width='99%' height='150' name='attach' ID='attach'  src='".$this->uri(array('action'=>'fileupload','emailId'=>$emailId))."'></iframe>";
    
    $left="&nbsp;";

    $objTableClass=$this->newObject('htmltable','htmlelements');
    $objTableClass->width='99%';
    $objTableClass->attributes=" border=0 align='top'";
    $objTableClass->cellspacing='5';
    $objTableClass->cellpadding='5';
    
    $objTableClass->startRow();
    $objTableClass->addCell($left,NULL,"top",'left');
    $objTableClass->addCell($form5,NULL,"top");
    $objTableClass->addCell($form4,NULL,"top");
    $objTableClass->endRow();

    // Finally the nested tables are output here.
    print $objTableClass->show();
    print $this->linkLine();

?>
