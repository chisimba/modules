<?php

  /**
   *template to display the travel expense output
   */
/******************************************************************************************************************************/   
    /**
      *load all classes
      */
      
      $this->loadClass('htmlheading', 'htmlelements');
      $this->loadClass('label','htmlelements');
      $this->loadClass('form','htmlelements');
      $this->loadClass('htmltable','htmlelements');
      $this->loadClass('tabbedbox', 'htmlelements');
      $this->loadClass('button','htmlelements');
      $this->loadClass('checkbox','htmlelements');
/*******************************************************************************************************************************/

      /**
       *define all language elements
       */
       
       $expensesheet  = $this->objLanguage->languageText('mod_onlineinvoice_travelsheet','onlineinvoice');
       $travelerdetails = $this->objLanguage->languageText('mod_onlineinvoice_travelerinfo','onlineinvoice');
       
/*******************************************************************************************************************************/
      /**
       *create all form headings
       */             

       $strheading  = strtoupper($expensesheet);
       $objtravelsheet  = new htmlHeading();
       $objtravelsheet->type  = 1;
       $objtravelsheet->str = $strheading;
       
       
/********************************************************************************************************************************/  
     /**
      *create table to place all elements in for traveler infor
      */
      
      //get all created by and modified etc from user and out of table format structure
      
      
      $limit = NULL;
      $invoicedates[] = $this->getSession('invoicedata');
      $arrayinvoice = new htmltable('invoicedates');
      $arrayinvoice->arrayToTable($invoicedates,$limit);
      
      
      $travelerinfo[] = $this->getSession('claimantdata');
      $arrayTable = new htmltable('travelerinfo');
      $arrayTable->arrayToTable($travelerinfo,$limit);
      //$this->unsetSession('claimantdata');
      
      /**
       *table for all itinerary information
       */
      $itineraryinfo = $this->getSession('addmultiitinerary');
      $itineraryTable = new htmltable('itineraryinfo');
      $itineraryTable->arrayToTable($itineraryinfo,$limit);  
      //$this->unsetSession('addmultiitinerary');
      /**
       *table for all  per diem expenses
       */
      $perdieminfo[]  = $this->getSession('perdiemdetails');
      $perdiemTable = new htmltable('perdieminfo');
      $perdiemTable->arrayToTable($perdieminfo,$limit);
      //$this->unsetSession('perdiemdetails');
      
                               
           
/********************************************************************************************************************************/
    /**
     *create a tabbed box to place arraytable in
     */
     
     $objtabbedinvoice = new tabbedbox();
     $objtabbedinvoice->addTabLabel('Invoice Dates');
     $objtabbedinvoice->addBoxContent("<div align=\"center\">" . '<br />' .$arrayinvoice->show());
     
     $objtabbedbox = new tabbedbox();
     $objtabbedbox->addTabLabel('Traveler Information');
     $objtabbedbox->addBoxContent("<div align=\"center\">" . '<br />' . $arrayTable->show()  . "</div>"  . '<br />');        
     
     /**
      *itinerary tabbed box
      */
      
     $objitinerarybox = new tabbedbox();
     $objitinerarybox->addTabLabel('Intinerary Information');
     $objitinerarybox->addBoxContent("<div align=\"center\">" . '<br />' . $itineraryTable->show()  . "</div>"  . '<br />');
     
     /**
      *perdiem tabbed box
      */
     $objperdiembox = new tabbedbox();
     $objperdiembox->addTabLabel('Per Diem Information');
     $objperdiembox->addBoxContent("<div align=\"center\">" . '<br />' . $perdiemTable->show()  . "</div>"  . '<br />');                       
                  
/********************************************************************************************************************************/  
         
      /**
       *display the output
       */
       
       echo  "<div align=\"center\">" . $objtravelsheet->show() . "</div>";
       echo   '<br />'   . $objtabbedinvoice->show();               
       echo   '<br />'  . $objtabbedbox->show();
       echo   '<br />'  . $objitinerarybox->show();
       echo   '<br />'  . $objperdiembox->show();    

?>
