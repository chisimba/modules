<?php
	$objButtons=&$this->getObject('navbuttons','navigation');
    if ($moduleList)
    {
        $tblclass=$this->newObject('htmltable','htmlelements');
        $tblclass->width='';
        $tblclass->attributes=" align='' border='0' ";
        $tblclass->cellspacing='5';
        $tblclass->cellpadding='5';
        foreach ($moduleList as $moduleRow)
        {
            $tblclass->startRow();
            $link=$objButtons->pseudoButton($this->uri(array(),$moduleRow['module_id'] ),$moduleRow['title']);
            $tblclass->addCell($link, "", Null, 'left', 'heading', '');
            //$tblclass->endRow();

            //$tblclass->startRow();
            $tblclass->addCell($moduleRow['description'], "", Null, 'left', 'even', '');
            $tblclass->endRow();
        } // end foreach
        print $tblclass->show();
     } //end if

?>