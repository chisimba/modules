<?php

  /**
   *template to display the travel expense output
   */
/******************************************************************************************************************************/   
    /**
      *load all classes
      */
      
/**      $this->loadClass('htmlheading', 'htmlelements');
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
 *claimant details
 */  
 
// $sessionClaimant []= $this->getSession('claimantdata');
 
// if(!empty($sessionClaimant)){
//Create table to display dates in session and the rates for breakfast, lunch and dinner and the total rate 
//  $objClaimantTable =& $this->newObject('htmltable', 'htmlelements');
/*  $objClaimantTable->cellspacing = '1';
  $objClaimantTable->cellpadding = '2';
  $objClaimantTable->border='1';
  $objClaimantTable->width = '100%';
  $objClaimantTable->cssClass = 'webfx-tab-style-sheet';
  $objClaimantTable->footing = 'Please submit or edit information';
  
  $objClaimantTable->startHeaderRow();
  $objClaimantTable->addHeaderCell('Name');
  $objClaimantTable->addHeaderCell('Title' );
  $objClaimantTable->addHeaderCell('Address');
  $objClaimantTable->addHeaderCell('City');
  $objClaimantTable->addHeaderCell('Province');
  $objClaimantTable->addHeaderCell('Postal Code');
  $objClaimantTable->addHeaderCell('Country');
  $objClaimantTable->addHeaderCell('Travel Purpose');
  $objClaimantTable->endHeaderRow();

  
  $rowcount = '0';
  
  foreach($sessionClaimant as $sesClaim){
     
     $oddOrEven = ($rowcount == 0) ? "odd" : "even";
     
     $objClaimantTable->startRow();
     $objClaimantTable->addCell($sesClaim['name'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['title'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['address'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['city'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['province'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['postalcode'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['country'], '', '', '', $oddOrEven);
     $objClaimantTable->addCell($sesClaim['travelpurpose'], '', '', '', $oddOrEven);
     $objClaimantTable->endRow();
  }
}

/**
 *create a tabbed box
 */
 
/*$this->loadClass('tabbedbox', 'htmlelements');
$objcreateinvtab = new tabbedbox();
$objcreateinvtab->addTabLabel('Traveler Information');
$objcreateinvtab->addBoxContent('<br />' .$objClaimantTable->show());// . '<br />' . "<div align=\"left\">" . $this->objSave->show() . ' ' . $this->objEdit->show()  ."</div>" . '<br />');
 

/***************************************************************************************************************************************************************/ 
/**
 *create form to place form buttons in
 */
 
/*$this->loadClass('form','htmlelements');
$objForm = new form('claiminfo',$this->uri(array('action'=>'claimantoutput')));
$objForm->displayType = 3;
$objForm->addToForm($objcreateinvtab->show());	

$objElement = & $this->getObject('tabpane', 'htmlelements');
// $objElement->addTab(array('name'=>'Invoice Information','','content' => $objtabbedinvoice->show()));
 $objElement->addTab(array('name'=>'Traveler Information','url'=>'http://localhost','content' => $objForm->show()));
 $objElement->addTab(array('name'=>'Intinerary Information','url'=>'http://localhost','content' => $objForm->show()));
 $objElement->addTab(array('name'=>'Per Diem Information','url'=>'http://localhost','content' => $objForm->show()));
 $objElement->addTab(array('name'=>'Lodge Expenses','url'=>'http://localhost','content' => $objForm->show()));
 $objElement->addTab(array('name'=>'Incident Expenses','url'=>'http://localhost','content' => $objForm->show()));
 

echo "<div align=\"center\">" . $objtravelsheet->show(). "</div>". '<br />' . '<br />';

if(!empty($sessionClaimant)){
  echo "<div align=\"left\">" . $objElement->show() . "</div>";
}

/***************************************************************************************************************************************************************/

  echo  "<div align=\"center\">" .  $objtravelsheet->show() . "</div>";
  echo '<br />' ."<div align=\"center\">" .'THIS PAGE IS UNDER CONSTRUCTION --- THANK YOU '. "</div>";          
?>
