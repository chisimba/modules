<?php 
/* modules/modulelist/templates/content/list_tpl.php
   A PHP template for the list modules output.  
   
   NOTES: Derek, I removed the stripslashes calls as I couldn't really see the necessity for it.
          Also module_path is now not really a path, maybe should be renamed 
		  DEREK SAYS:  What if the title or description has a \' in it?
   
   NOTES: Derek says - corrected line 24 to read $moduleRow['description']. 
   *  Agree module_path should be renamed. Shouldn't it be module code?
   */

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
