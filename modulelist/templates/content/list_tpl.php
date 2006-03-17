<?php 

    $objButtons=&$this->getObject('navbuttons','navigation');
    if ($moduleList)
    { 
        $tblclass=$this->newObject('htmltable','htmlelements');
        $tblclass->width='';
        $tblclass->attributes=" align='' border=0 ";
        $tblclass->cellspacing='2';
        $tblclass->cellpadding='2';
        foreach ($moduleList as $moduleRow) 
        {
            $tblclass->startRow();
            $link=$objButtons->pseudoButton($this->uri(array(),$moduleRow['module_id'] ),$moduleRow['title']);
            $tblclass->addCell($link, "", Null, 'center', 'heading', '');
            $tblclass->endRow();

            $tblclass->startRow();
            $tblclass->addCell($moduleRow['description'], "", Null, 'center', 'even', '');
            $tblclass->endRow();
        } // end foreach 
        print $tblclass->show();
     } //end if 
    
?>