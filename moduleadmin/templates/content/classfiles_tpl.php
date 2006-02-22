<?
    $objButtons=&$this->getObject('navbuttons','navigation');
                                                                                                                                             
    $objTblclass=$this->newObject('htmltable','htmlelements');
    $objTblclass->width='';
    $objTblclass->attributes=" align='center' border=2";
    $objTblclass->cellspacing='2';
    $objTblclass->cellpadding='2';

    $objTblclass->startRow();
    $objTblclass->addCell($objLanguage->languageText('mod_phrase_classfiles','Class Files in Module').': '.$modname, "", NULL, 'center', 'heading', 'colspan=1');
    $objTblclass->endRow();
    foreach ($classes as $line)
    {
        $objTblclass->addRow(array($line),'odd');
    }
    print $objTblclass->show();
    
    print $objButtons->pseudoButton($this->uri(array()),$objLanguage->languagetext('word_back','Back'));

 
?>
