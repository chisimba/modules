<?php

  /**
   *create a template for service information
   */
   
/***************************************************************************************************************************************************************/   
   /**
    *create main heading
    */
     $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
     $this->objMainheading->type=1;
    $this->objMainheading->str=ucfirst($objLanguage->languageText('mod_onlineinvoice_serviceinformation','onlineinvoice'));   
/***************************************************************************************************************************************************************/
  /**
   *language items
   */     
   
   $empname     = $this->objLanguage->languageText('mod_onlinveinvoice_empname','onlineinvoice');
   $monthlyrate = $this->objLanguage->languageText('mod_onlinveinvoice_monthlyrate','onlineinvoice');
   $fte         = $this->objLanguage->languageText('mod_onlinveinvoice_fte','onlineinvoice');
   $formonth    = $this->objLanguage->languageText('mod_onlinveinvoice_formonth','onlineinvoice');
   //$begindate   = 
   
   /**
    *create a table for all form elements
    */
    /*create table to place form elements in  --  date values*/
        $myTable=$this->newObject('htmltable','htmlelements');
        $myTable->width='60%';
        $myTable->border='0';
        $myTable->cellspacing='1';
        $myTable->cellpadding='10';

        $myTable->startRow();
        $myTable->addCell(ucfirst($empname));
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell(ucfirst($monthlyrate));
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell(ucfirst($fte));
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell(ucfirst($formonth));
        $myTable->endRow();
           
   
/***************************************************************************************************************************************************************/   
   /**
    *display all screen outputs
    */
    echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
    echo  "<div align=\"left\">" . $myTable->show() . "</div>";
                   

?>
