<?
$objConfirm = &$this->newObject('confirm','utilities');
$objAlphabet=& $this->getObject('alphabet','navigation');

 if (isset($output))
 {
     print "<b>".$output."</b>";
 }
 
    $this->loadclass('href','htmlelements');
    $href= new href(); // hypertext link class
    
    $objSkin = & $this->getObject('skin','skin');
    $this->icon =& $this->getObject('geticon', 'htmlelements');
    
    $objButtons=&$this->getObject('navbuttons','navigation');

    $objTblclass=$this->newObject('htmltable','htmlelements');
    $objTblclass->active_rows=TRUE;
    $objTblclass->width='';
    $objTblclass->attributes=" align='center' border=0";
    $objTblclass->cellspacing='2';
    $objTblclass->cellpadding='2';

    $objTblclass->startRow();
    // Additional Column for Icon
    $objTblclass->addCell('&nbsp;', "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->addCell($objLanguage->languageText('mod_word_module','Module'), "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->addCell($objLanguage->languageText('has_controller','Has Controller'), "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->addCell($objLanguage->languageText('has_registration_file','Has Registration File'), "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->addCell($objLanguage->languageText('has_classes','Has Classes'), "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->addCell($objLanguage->languageText('is_registered','Is Registered'), "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->addCell($objLanguage->languageText('mod_word_options','Options'), "", NULL, 'center', 'heading', 'colspan=3');
    $objTblclass->endRow();

    $boolean=array('0'=>'N','1'=>'Y','2'=>'Y');

    $attrib='colpan=1';
    $rowcount=1; // this will control odd or even lines
    foreach ($modulelist as $module=>$data)
    {
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
        
        // Check for Module Icon
        if(file_exists($objSkin->getSkinLocation().'icons/modules/'.$module.'.gif')){ 
					$this->icon->setModuleIcon($module);
				} else { $this->icon->setIcon('modules/default'); }
                
        $this->icon->setModuleIcon($module);
        
        $objTblclass->addCell($this->icon->show(), "", NULL, 'left', $cellClass, $attrib);
        // End Showing of Icon
        
        $objTblclass->addCell("<b>&nbsp;".$moduletext."</b>", "", NULL, 'left', $cellClass, $attrib);
        foreach ($data as $flag)
        {
            $objTblclass->addCell($boolean[$flag], "", NULL, 'center', $cellClass, $attrib);
        }
        $links=array('text'=>'&nbsp;','reg'=>'&nbsp;','classes'=>'&nbsp;','info'=>'&nbsp');
        
        //if (($data['isReg']==0)&&($data['hasController'])&&($data['hasRegFile'])) // testig new conditions
        if (($data['isReg']==0)&&($data['hasRegFile']))
        {
            $links['reg']= $objButtons->pseudoButton($this->uri(array('action'=>'register','modname'=>$module)),$objLanguage->languagetext('mod_word_register','Register'));
        }    
        if ($data['hasClasses'])
        {
            $links['classes']=$objButtons->pseudoButton($this->uri(array('action'=>'show classes','modname'=>$module)),$objLanguage->languagetext('mod_show_classes','Show Classes'));
        }
        if ($data['isReg']==1){
            $deleteLink=$this->uri(array('action'=>'deregister','modname'=>$module));
            $objConfirm->setConfirm($objLanguage->languagetext('mod_word_deregister','Deregister'),$deleteLink,$this->confirmRegister('mod_moduleadmin_deregsure',$module,FALSE),"class='pseudobutton'");
            $links['reg']=$objConfirm->show();
        }    
        if ($data['hasRegFile'])
        {
            $links['text']=$objButtons->pseudoButton($this->uri(array('action'=>'textelements','modname'=>$module)),$objLanguage->languagetext('mod_word_textelements','Text Elements'));
            $links['info']=$objButtons->pseudoButton($this->uri(array('action'=>'info','modname'=>$module)),$objLanguage->languagetext('mod_moduleadmin_info2','Module Info'));
        }    

        $objTblclass->addCell($links['reg'], "", NULL, NULL, $cellClass, $attrib);
        $objTblclass->addCell($links['text'], "", NULL, NULL, $cellClass, $attrib);
        //$objTblclass->addCell($links['classes'], "", NULL, NULL, $cellClass, $attrib);
        $objTblclass->addCell($links['info'], "", NULL, NULL, $cellClass, $attrib);
        $objTblclass->endRow();
    }
 
    $objTbl2=$this->newObject('htmltable','htmlelements');
    $objTbl2->width='100%';
    $objTbl2->addRow(array('',$objTblclass->show()));
    //print $objTblclass->show();
    print "<div align='center'>\n";
    print "<h1>".$objLanguage->languageText('mod_moduleadmin_name')."</h1>\n";
    print "<a href='".$this->uri(array('action'=>'batch'))."'>".$objLanguage->languageText('mod_moduleadmin_batch1','Batch Registration Menu')."</a><br \>\n";
    print "<a href='".$this->uri(array('action'=>'deregisterbatch'))."'>".$objLanguage->languageText('mod_moduleadmin_deregisterbatch1','Batch DeRegistration Menu')."</a><br \>\n";

    print $objAlphabet->putAlpha($this->uri(array('action'=>$this->getParam('action'),'filter'=>'LETTER')),FALSE);
    print $objTbl2->show();
    print "</div>";

?>


