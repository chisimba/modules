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
       $save  = $this->objLanguage->languageText('word_save');
       $edit  = $this->objLanguage->languageText('word_edit');
       
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
       *create save an edit buttons
       */
       
       //create all form buttons
      
      $strsave = ucfirst($save);
      $this->loadclass('button','htmlelements');
      $this->objSave  = new button('save', $strsave);
      $this->objSave->setToSubmit();
      
      $stredit = ucfirst($edit);
      //$this->loadclass('button','htmlelements');
      $this->objEdit  = new button('edit', $stredit);
      $this->objEdit->setToSubmit();             

/********************************************************************************************************************************/ 
     /**
      *create table to place all elements in for traveler infor
      */
      
      //get all created by and modified etc from user and out of table format structure
      
      
      $limit = NULL;
      /*$invoicedates[] = $this->getSession('invoicedata');
      $arrayinvoice = new htmltable('invoicedates');
      //$this->alternate_row_colors = true;
      $arrayinvoice->arrayToTable($invoicedates,$limit);*/
      
      
      $travelerinfo[] = $this->getSession('claimantdata');
      $arrayTable = new htmltable('travelerinfo');
      $arrayTable->alternate_row_colors = TRUE;     /*does not work, suppose to change the row to different colour*/
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
      
      /**
       *table fot all lodge expenses
       */
       $lodgeinfo[] = $this->getSession('lodgedetails');
       $lodgeTable  = new htmltable('lodgedata');
       $lodgeTable->arrayToTable($lodgeinfo,$limit);      
       
       /**
        *table for incident expenses
        */
       $incidentinfo[] = $this->getSession('incidentdetails');
       $incidentTable  = new htmltable('incidentdate');
       $incidentTable->arrayToTable($incidentinfo,$limit);                      
                               
           
/********************************************************************************************************************************/
    /**
     *create a tabbed box to place arraytable in
     */
     
    // $objtabbedinvoice = new tabbedbox();
     //$objtabbedinvoice->addTabLabel('Invoice Dates');
     //$objtabbedinvoice->addBoxContent("<div align=\"center\">" . '<br />' .$arrayinvoice->show());
     
     /**
      *tabbed box for tev info
      */           
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
     
     /**
      *lodge info
      */                 
     
     $objlodgebox = new tabbedbox();
     $objlodgebox->addTabLabel('Lodge Expenses');
     $objlodgebox->addBoxContent("<div align=\"center\">" . '<br />' . $lodgeTable->show()  . "</div>"  . '<br />');
     
     /**
      *incident info      
      */ 
      $objincidentbox = new tabbedbox();
      $objincidentbox->addTabLabel('Incident Expenses');
      $objincidentbox->addBoxContent("<div align=\"center\">" . '<br />' . $lodgeTable->show()  . "</div>"  . '<br />');
      
            
/********************************************************************************************************************************/  

    /**
     *create form to place all button elements on
     */

     $objtevoutput  = new form('tevoutput',$this->uri(array('action'=>'NULL')));
     $objtevoutput->displayType = 3;
     $objtevoutput->addToForm($this->objSave->show() . $this->objEdit->show());            
/********************************************************************************************************************************/ 

 $objElement = & $this->getObject('tabpane', 'htmlelements');
// $objElement->addTab(array('name'=>'Invoice Information','','content' => $objtabbedinvoice->show()));
 $objElement->addTab(array('name'=>'Traveler Information','url'=>'http://localhost','content' => $objtabbedbox->show()));
 $objElement->addTab(array('name'=>'Intinerary Information','url'=>'http://localhost','content' => $objitinerarybox->show()));
 $objElement->addTab(array('name'=>'Per Diem Information','url'=>'http://localhost','content' => $objperdiembox->show()));
 $objElement->addTab(array('name'=>'Lodge Expenses','url'=>'http://localhost','content' => $objlodgebox->show()));
 
 $objElement->addTab(array('name'=>'Incident Expenses','url'=>'http://localhost','content' => $objincidentbox->show()));
  echo $objElement->show();
  //die;

/********************************************************************************************************************************/         
      /**
       *display the output
       */
       
    /*   echo  "<div align=\"center\">" . $objtravelsheet->show() . "</div>";
       echo   '<br />'   . $objtabbedinvoice->show();               
       echo   '<br />'  . $objtabbedbox->show();
       echo   '<br />'  . $objitinerarybox->show();
       echo   '<br />'  . $objperdiembox->show(); 
       echo   '<br />'  . $objlodgebox->show();
       echo   '<br />'  . $objincidentbox->show();
       echo   '<br />'  . $objtevoutput->show();*/   

?>
