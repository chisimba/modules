<?
    $objConfirm = &$this->newObject('confirm','utilities');
    $objAlphabet=& $this->getObject('alphabet','navigation');
    $objForm=&$this->newObject('form','htmlelements');
    $objCheck=&$this->newObject('checkbox','htmlelements');
    $objButton=&$this->newObject('button','htmlelements');
    $objTextinput=&$this->newObject('textinput','htmlelements');

    print $this->getJavascriptFile('selectall.js');

 if (isset($output))
 {
     print "<b>".$output."</b>";
 }

    if (!isset($registerFlag)){
        $registerFlag=0;
    }
    if (!isset($batchAction)){
        $batchAction='batchregister';
    }
 
    $this->loadclass('href','htmlelements');
    $href= new href(); // hypertext link class
    
    $objSkin = & $this->getObject('skin','skin');
    $this->icon =& $this->getObject('geticon', 'htmlelements');
    
    $objButtons=&$this->getObject('navbuttons','navigation');

    $objTblclass=$this->newObject('htmltable','htmlelements');
    $objTblclass->active_rows=TRUE;
    $objTblclass->width='';
    $objTblclass->attributes=" align='center' border='0'";
    $objTblclass->cellspacing='2';
    $objTblclass->cellpadding='2';

    $objTblclass->startRow();
    // Additional Column for Icon
    $objTblclass->addCell('&nbsp;', "", NULL, 'center', 'heading', 'colspan="2"');
    $objTblclass->addCell($objLanguage->languageText('mod_word_module','moduleadmin','Module'), "", NULL, 'center', 'heading', 'colspan="1"');
    $objTblclass->addCell($objLanguage->languageText('has_controller','moduleadmin','Has Controller'), "", NULL, 'center', 'heading', 'colspan="1"');
    $objTblclass->addCell($objLanguage->languageText('has_registration','moduleadmin','Has Registration File'), "", NULL, 'center', 'heading', 'colspan="1"');
    $objTblclass->addCell($objLanguage->languageText('has_classes','moduleadmin','Has Classes'), "", NULL, 'center', 'heading', 'colspan="1"');
    $objTblclass->addCell($objLanguage->languageText('is_registered','moduleadmin','Is Registered'), "", NULL, 'center', 'heading', 'colspan="1"');
    $objTblclass->addCell($objLanguage->languageText('mod_word_options','moduleadmin','Options'), "", NULL, 'center', 'heading', 'colspan="3"');
    $objTblclass->endRow();

    $boolean=array('0'=>'N','1'=>'Y','2'=>'Y');

    $attrib='colspan="1"';
    $rowcount=1; // this will control odd or even lines
    foreach ($modulelist as $module=>$data)
    {
        // Code for the checkbox - only display if module is registerable
        if (($data['isReg']==$registerFlag)&&($data['hasRegFile'])){
            $objCheck->checkbox('arrayList[]');
            $objCheck->setValue($module);
            $checkBox=$objCheck->show();
        } else {
            $checkBox='&nbsp;';
            continue;
        }
        
        $rowcount=($rowcount==0) ? 1 : 0; 
        $oddOrEven=($rowcount==0) ? "odd" : "even";
        $objTblclass->trClass=$oddOrEven;
        $cellClass=NULL;

        $moduletext=$module;
        if ($data['hasController'])
        {
            $moduletext=$href->showlink($this->uri(array(),$module),$module);
        }
        $objTblclass->row_attributes=" onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='".$oddOrEven."'; \"";
        $objTblclass->startRow();
        $objTblclass->addCell($checkBox);

        // Check for Module Icon
        $this->icon->setModuleIcon($module);
        $objTblclass->addCell($this->icon->show(), "", NULL, 'left', $cellClass, $attrib);
        // End Showing of Icon
        
        $objTblclass->addCell("<b>&nbsp;".$moduletext."</b>", "", NULL, 'left', $cellClass, $attrib);
        foreach ($data as $flag)
        {
            $objTblclass->addCell($boolean[$flag], "", NULL, 'center', $cellClass, $attrib);
        }
        $links=array('text'=>'&nbsp;','reg'=>'&nbsp;','classes'=>'&nbsp;');
        
        if (($data['isReg']==0)&&($data['hasRegFile']))
        {
            $links['reg']= $objButtons->pseudoButton($this->uri(array('action'=>'register','modname'=>$module)),$objLanguage->languagetext('mod_word_register','moduleadmin','Register'));
        }    
        if ($data['hasClasses'])
        {
            $links['classes']=$objButtons->pseudoButton($this->uri(array('action'=>'show classes','modname'=>$module)),$objLanguage->languagetext('mod_show_classes','moduleadmin','Show Classes'));
        }
        if ($data['isReg']==1)
        {
            $deleteLink=$this->uri(array('action'=>'deregister','modname'=>$module));
            $objConfirm->setConfirm($objLanguage->languagetext('mod_word_deregister','moduleadmin','Deregister'),$deleteLink,$this->confirmRegister('mod_moduleadmin_deregsure',$module,FALSE),"class='pseudobutton'");
            $links['reg']=$objConfirm->show();
        }    
        if ($data['hasRegFile'])
        {
            $links['text']=$objButtons->pseudoButton($this->uri(array('action'=>'textelements','modname'=>$module)),$objLanguage->languagetext('mod_word_textelements','moduleadmin','Text Elements'));
            $links['info']=$objButtons->pseudoButton($this->uri(array('action'=>'info','modname'=>$module)),$objLanguage->languagetext('mod_moduleadmin_info2','moduleadmin','Module Info'));
        }    

        $objTblclass->addCell($links['reg'], "", NULL, NULL, $cellClass, $attrib);
        $objTblclass->addCell($links['text'], "", NULL, NULL, $cellClass, $attrib);
        $objTblclass->addCell($links['info'], "", NULL, NULL, $cellClass, $attrib);
        $objTblclass->endRow();
    }
 
    //print $objTblclass->show();
    
    print "<div align='center'>\n";
    print "<h1>".$objLanguage->languageText('mod_moduleadmin_name','moduleadmin')."</h1>\n";
    

    //Start building up the form
    $objForm->form('BatchRegister',$this->uri(array('action'=>$batchAction)));
    
    // Hidden text fields
    $objTextinput->textinput('module','moduleadmin');
    $objTextinput->fldType='hidden';
    $objForm->addToForm($objTextinput->show());
    
    $objTextinput->textinput('action',$batchAction);
    $objTextinput->fldType='hidden';
    $objForm->addToForm($objTextinput->show());
 
    // This is where used to load all the above into the main table
    // Now its been moved to below the buttons
    
    // Buttons to Select All/Deselect
    $selectbutton=$this->newObject('button','htmlelements'); 
    $selectbutton->setOnClick("javascript:SetAllCheckBoxes('BatchRegister', 'arrayList[]', true);"); 
    $selectbutton->setValue($this->objLanguage->languageText('text_selectall','moduleadmin','Select All')); 

    $unselectbutton=$this->newObject('button','htmlelements'); 
    $unselectbutton->setOnClick("javascript:SetAllCheckBoxes('BatchRegister', 'arrayList[]', false);"); 
    $unselectbutton->setValue($this->objLanguage->languageText('text_selectnone','moduleadmin','Select None'));

    $objForm->addToForm("<p align='center'>\n"); 

    $objForm->addToForm($selectbutton); 
    $objForm->addToForm("&nbsp;"); 
    $objForm->addToForm($unselectbutton); 
    $objForm->addToForm("&nbsp;"); 
    //$objForm->addToForm("<br />\n");
                                    
    
    // the submit button
    if ($registerFlag==1){
        $regtext=$objLanguage->languageText('mod_word_deregister','moduleadmin','Deregister');
    } else {
        $regtext=$objLanguage->languageText('mod_word_register','moduleadmin','Register');
    }
    $objButton->button('submit',$regtext);
    $objButton->setToSubmit();
    $objForm->addToForm($objButton->show());
    
    $objForm->addToForm("</p>\n"); 
                
    // The main table so far
    $objForm->addToForm($objTblclass->show());

    // Add the buttons again
    $objForm->addToForm("<p align='center'>\n"); 
    $objForm->addToForm($selectbutton); 
    $objForm->addToForm("&nbsp;"); 
    $objForm->addToForm($unselectbutton); 
    $objForm->addToForm("&nbsp;"); 
    $objForm->addToForm($objButton->show()); 
    $objForm->addToForm("</p>\n"); 


    // Output the main table here
    $objTbl2=$this->newObject('htmltable','htmlelements');
    $objTbl2->width='100%';
    $objTbl2->addRow(array('',$objForm->show()));

    if ($batchAction!='batchregister'){
        print "<a href='".$this->uri(array('action'=>'batch'))."'>".$objLanguage->languageText('mod_moduleadmin_batch1','moduleadmin','Batch Registration Menu')."</a><br />\n";
    }
    if ($batchAction!='batchderegister'){
        print "<a href='".$this->uri(array('action'=>'deregisterbatch'))."'>".$objLanguage->languageText('mod_moduleadmin_deregisterbatch1','moduleadmin','Batch DeRegistration Menu')."</a><br />\n";
    }
        
    print $objAlphabet->putAlpha($this->uri(array('action'=>$this->getParam('action'),'filter'=>'LETTER')),FALSE);
    print $objTbl2->show();
    
    print "</div>";

?>