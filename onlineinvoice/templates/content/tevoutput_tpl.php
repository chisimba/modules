<?php

  /**
   *template to display the travel expense output
   */
/******************************************************************************************************************************/   
    /**
      *load all classes / create objects of all classes
      */
     $claimantinfo  = & $this->getObject('dbtev','onlineinvoice');
     $display = $claimantinfo->showclaimant();
     
     $itinerary = & $this->getObject('dbitinerary','onlineinvoice');
     $results = $itinerary->showtitinerary();
     
     $perdiem = & $this->getObject('dbperdiem','onlineinvoice');
     $showperdiem = $perdiem->showperdiem();
     
     $lodge = & $this->getObject('dblodging','onlineinvoice');
     $showlodge = $lodge->showlodge();
     
     $incident  = & $this->getObject('dbincident','onlineinvoice');
     $showincident  = $incident->showincident();
     
     $output = $display  . '<br />'  . $results . '<br />' . $showperdiem . '<br />' . $showlodge . '<br />' . $showincident;
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
 *create a tabbed box
 */
 
$this->loadClass('tabbedbox', 'htmlelements');
$objcreateinvtab = new tabbedbox();
$objcreateinvtab->addTabLabel('Lodge Information');
$objcreateinvtab->addBoxContent('<br />' .$showlodge);// . '<br />' . "<div align=\"left\">" . $this->objSave->show() . ' ' . $this->objEdit->show()  ."</div>" . '<br />');

$objcreatetraveler = new tabbedbox();
$objcreatetraveler->addTabLabel('Traveler Information');
$objcreatetraveler->addBoxContent('<br />' .$display);// . '<br />' . "<div align=\"left\">" . $this->objSave->show() . ' ' . $this->objEdit->show()  ."</div>" . '<br />');

$objcreateitinerary = new tabbedbox();
$objcreateitinerary->addTabLabel('Itinerary Information');
$objcreateitinerary->addBoxContent('<br />' .$results);// . '<br />' . "<div align=\"left\">" . $this->objSave->show() . ' ' . $this->objEdit->show()  ."</div>" . '<br />');

$objcreateperdiem = new tabbedbox();
$objcreateperdiem->addTabLabel('Per Diem Information');
$objcreateperdiem->addBoxContent('<br />' .$showperdiem);// . '<br />' . "<div align=\"left\">" . $this->objSave->show() . ' ' . $this->objEdit->show()  ."</div>" . '<br />');

$objcreateincident = new tabbedbox();
$objcreateincident->addTabLabel('Incident Information');
$objcreateincident->addBoxContent('<br />' .$showincident);// . '<br />' . "<div align=\"left\">" . $this->objSave->show() . ' ' . $this->objEdit->show()  ."</div>" . '<br />');

     
/***************************************************************************************************************************************************************/

  /**
       *create links to move to next section
       */
       
    $urltext = 'travel expense voucher';
    $content = 'complete a travel voucher for the traveler';
    $caption = '';
    $url = $this->uri(array('action'=>'createtev'));
    $this->objinvdates  = & $this->newObject('mouseoverpopup','htmlelements');
    $this->objinvdates->mouseoverpopup($urltext,$content,$caption,$url);                        
    
    $urltext = 'itinerary';
    $content = 'complete an itinerary for the travel';
    $caption = '';
    $url = $this->uri(array('action'=>'createitinerary'));
    $this->objinvitinerary  = & $this->newObject('mouseoverpopup','htmlelements');
    $this->objinvitinerary->mouseoverpopup($urltext,$content,$caption,$url);
    
    $urltext = 'per diem expenses';
    $content = 'complete all per diem expenses';
    $caption = '';
    $url = $this->uri(array('action'=>'showperdiem'));
    $this->objinvperdiem  = & $this->newObject('mouseoverpopup','htmlelements');
    $this->objinvperdiem->mouseoverpopup($urltext,$content,$caption,$url);
    
    $urltext = 'lodge expenses';
    $content = 'complete all lodge expenses';
    $caption = '';
    $url = $this->uri(array('action'=>'createlodge'));
    $this->objinvlodge  = & $this->newObject('mouseoverpopup','htmlelements');
    $this->objinvlodge->mouseoverpopup($urltext,$content,$caption,$url);
     
    $urltext = 'incident expenses';
    $content = 'complete all incident expenses';
    $caption = '';
    $url = $this->uri(array('action'=>'showincident'));
    $this->objinvincidents  = & $this->newObject('mouseoverpopup','htmlelements');
    $this->objinvincidents->mouseoverpopup($urltext,$content,$caption,$url);
    
    $myTab = $this->newObject('htmltable','htmlelements');
    $myTab->width='100%';
    $myTab->border='0';
    $myTab->cellspacing='5';
    $myTab->cellpadding='5';
   
    $myTab->startRow();
    $myTab->addCell(ucfirst($this->objinvdates->show()));
    $myTab->endRow();
    
    $myTab->startRow();
    $myTab->addCell(ucfirst($this->objinvitinerary->show()));
    $myTab->endRow();
    
    $myTab->startRow();
    $myTab->addCell(ucfirst($this->objinvperdiem->show()));
    $myTab->endRow();
    
    $myTab->startRow();
    $myTab->addCell(ucfirst($this->objinvlodge->show()));
    $myTab->endRow();
    
    $myTab->startRow();
    $myTab->addCell(ucfirst($this->objinvincidents->show()));
    $myTab->endRow();
    
    
/**
 *create a tabpane
 */   
$output = '<br />' . $objcreatetraveler->show() .  '<br />' . $objcreateitinerary->show() . '<br />' . $objcreateperdiem->show()  . '<br />' . $objcreateinvtab->show() . '<br />' . $objcreateincident->show();
$objElement =& $this->newObject('tabpane', 'htmlelements');
$objElement->addTab(array('name'=>'Travel Output','content' => $output),'luna-tab-style-sheet');
$objElement->addTab(array('name'=>'Edit a Section','content' => $myTab->show()),'luna-tab-style-sheet');
    
     
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
  //echo '<br />' ."<div align=\"center\">" .'THIS PAGE IS UNDER CONSTRUCTION --- THANK YOU '. "</div>";
  //echo '<br />' . $objcreatetraveler->show();
  //echo '<br />' . $objcreateitinerary->show();
  //echo '<br />' . $objcreateperdiem->show();
  //echo '<br />' . $objcreateinvtab->show();
  //echo '<br />' . $objcreateincident->show();
  //echo '<br />' . $output;
  echo '<br />' . $objElement->show();          
?>
