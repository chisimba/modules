<?
    if (defined('OUTPUT'))
    {
        $objTblclass=$this->newObject('htmltable','htmlelements');
        $objTblclass->width='';
        $objTblclass->attributes=" align='center' border=0";
        $objTblclass->cellspacing='2';
        $objTblclass->cellpadding='2';
        $objTblclass->startRow();
        $objTblclass->addCell(OUTPUT, "", NULL, 'right', 'odd', 'colspan=1');
        $objTblclass->endRow();
        print $objTblclass->show();
    } 

    if ($this->output!='')
    {
        $objTblclass=$this->newObject('htmltable','htmlelements');
        $objTblclass->width='';
        $objTblclass->attributes=" align='center' border=0";
        $objTblclass->cellspacing='2';
        $objTblclass->cellpadding='2';
        $objTblclass->startRow();
        $objTblclass->addCell($this->output, "", NULL, NULL, 'odd', 'colspan=1');
        $objTblclass->endRow();
        print $objTblclass->show();
    } 


    // Now in case of errors
    if ($this->objModule->errorText) {
        //Create an instance of the table object
        $objTable = $this->newObject('htmltable', 'htmlelements');
        $objTable->width='';
        $objTable->attributes=" align='center' border=0";
        $objTable->startRow();
        $objTable->addCell('<b>'.$this->objLanguage->languageText('mod_moduleadmin_problem1','Problems detected').':</b>', "", NULL, NULL, 'odd');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($this->objModule->errorText, "", NULL, NULL, 'odd');
        $objTable->endRow();
        //Show the table
        echo $objTable->show();
    }
 
    // Now the button
    $objButtons=&$this->getObject('navbuttons','navigation');

    $objTblclass=$this->newObject('htmltable','htmlelements');
    $objTblclass->attributes=" align='center' border=0";
    $objTblclass->width='100%';
    $objTblclass->startRow();
    //$objTblclass->addCell($objButtons->pseudoButton($this->uri(array()),$objLanguage->languagetext('word_ok','OK')),"",NULL,'center',NULL,NULL);
    $objTblclass->addCell($objButtons->pseudoButton($this->uri(array()),$this->objLanguage->languageText('mod_moduleadmin_return')),"",NULL,'center',NULL,NULL);
    $objTblclass->addCell('','',NULL,NULL,NULL,NULL);
    $objTblclass->endRow();
    print $objTblclass->show();

    
?>

