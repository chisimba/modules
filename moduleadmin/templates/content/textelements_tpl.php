<?

    // Table to display the texts
    $objButtons=&$this->getObject('navbuttons','navigation');
    $objTblclass=$this->newObject('htmltable','htmlelements');
    $objTblclass->width='';
    $objTblclass->attributes=" align='center' border=0";
    $objTblclass->cellspacing='2';
    $objTblclass->cellpadding='2';
    
    $count=0;
    $missing=0;
    
    echo '<h1>'.$objLanguage->languagetext('mod_moduleadmin_textelementsfor').' <em>'.$modname.'</em></h1>';


    // Now in case of errors
    if ($this->objModule->errorText){
        $objTblclass->startRow();
        $objTblclass->addCell('<b>'.$this->objLanguage->languageText('mod_moduleadmin_problem1','Problems detected').':</b>', "", NULL, NULL, 'heading',"colspan=1");
        $objTblclass->addCell($this->objModule->errorText, "", NULL, NULL, 'odd','colspan=4');
        $objTblclass->endRow();
    }

    // Display the headings
    $objTblclass->startRow();
    $objTblclass->addCell("&nbsp;", "", NULL, NULL, 'heading', NULL);
    $objTblclass->addCell("register.conf", "", NULL, 'center', 'heading', 'colspan=2');
    $objTblclass->addCell("dbkewl", "", NULL, 'center', 'heading', 'colspan=2');
    $objTblclass->endRow(); 
    

    $objTblclass->addRow(array('code','description','content','description','content'),'heading');
    
    // Now build up the table from the supplied array $moduledata
    foreach ($moduledata as $line)
    {
        $row=array($line['code'],$line['desc'],$line['content'],$line['isreg']['desc'],$line['isreg']['content']);
        $objTblclass->addRow($row,'odd');
        $count=$count+1;
        if ($line['isreg']['flag']!=11)
        {
            $missing=$missing+1;
        }
    }
 

    // Table for the navigation buttons
    $objButtons=&$this->getObject('navbuttons','navigation');
    $objTbl2=$this->newObject('htmltable','htmlelements');
    $objTbl2->attributes=" align='center' border=0";
    $objTbl2->cellspacing='2';
    $objTbl2->width='30%';
    
    $objTbl2->startRow();
    
    // Button to add new texts
    if ($missing>0)
    {
        //$objTbl2->startRow();
        $addphrase=$objLanguage->languageText('mod_word_addtext','Add Missing Texts');
        $addlink=$objButtons->pseudoButton($this->uri(array('action'=>'addtext','modname'=>$modname)),$addphrase);
        $objTbl2->addCell($addlink,'',NULL,'center',NULL,NULL);
        //$objTbl2->endRow();
    }

    // Button to replace all Texts
    if ($count>0){ 
        $rphrase=$objLanguage->languageText('mod_moduleadmin_replacetext'); 
        $rlink=$objButtons->pseudoButton($this->uri(array('action'=>'replacetext','modname'=>$modname)),$rphrase); 
        $objTbl2->addCell($rlink,'',NULL,'center',NULL,NULL);
    }

    // Button to return to main menu
    $objTbl2->addCell($objButtons->pseudoButton($this->uri(array()),$objLanguage->languagetext('phrase_goback')),"",NULL,'center',NULL,NULL);
    
    $objTbl2->endRow();

    
    if ($count>0)
    {
        print $objTbl2->show();
        print $objTblclass->show();
        print $objTbl2->show();
    }
    else
    {
        print $objLanguage->languageText('mod_no_text',"No Text elements")."<br>\n";
        print $objTbl2->show();
    }

?>

