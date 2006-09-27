<?php
    /**
     *template to show pending invoices
     */
     
     //$this->loadClass('tabpane','htmelements');
     $cssLayout =& $this->newObject('csslayout', 'htmlelements');
     
     $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
     $this->objMainheading->type=1;
     $this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_webbasedinvoicingsystem','onlineinvoice');
     
     $claimantinfo  = & $this->getObject('dbtev','onlineinvoice');
     $display = $claimantinfo->showclaimant();
     
     $itinerary = & $this->getObject('dbitinerary','onlineinvoice');
     $results = $itinerary->showtitinerary();
     
     $perdiem = & $this->getObject('dbperdiem','onlineinvoice');
     $displayperdiem = $perdiem->showperdiem();
     
     $lodge = & $this->getObject('dblodging','onlineinvoice');
     $showlodge = $lodge->showlodge();
     
     $incident  = & $this->getObject('dbincident','onlineinvoice');
     $showincident  = $incident->showincident();
     
     /**
      *show outputs to date
      */
      //$limit = NULL;
     // $claimantinfo [] = $this->getSession('claimantdata');
      //$this->loadClass('htmltable','htmlelements');
      //$mytable = new htmltable('claimant');
      //$mytable->arrayToTable($claimantinfo,$limit);
/*********************************************************************************************************************************************/      
      
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
/*********************************************************************************************************************************/      
//     $output = $display  . '<br />'  . $results . '<br />' . $displayperdiem . '<br />' . $showlodge . '<br />' . $showincident;
/********************************************************************************************************************************/  

/**
 *create a tabbed box
 */
 
$this->loadClass('tabbedbox', 'htmlelements');
$objcreateinvtab = new tabbedbox();
$objcreateinvtab->addTabLabel('Lodge Information');
$objcreateinvtab->addBoxContent('<br />' .$showlodge);

$objcreatetraveler = new tabbedbox();
$objcreatetraveler->addTabLabel('Traveler Information');
$objcreatetraveler->addBoxContent('<br />' .$display);

$objcreateitinerary = new tabbedbox();
$objcreateitinerary->addTabLabel('Itinerary Information');
$objcreateitinerary->addBoxContent('<br />' .$results);

$objcreateperdiem = new tabbedbox();
$objcreateperdiem->addTabLabel('Per Diem Information');
$objcreateperdiem->addBoxContent('<br />' .$displayperdiem);

$objcreateincident = new tabbedbox();
$objcreateincident->addTabLabel('Incident Information');
$objcreateincident->addBoxContent('<br />' .$showincident);

     
/***************************************************************************************************************************************************************/
     
     
     
   $objElement =& $this->newObject('tabpane', 'htmlelements');
   $output = '<br />' . $objcreatetraveler->show() .  '<br />' . $objcreateitinerary->show() . '<br />' . $objcreateperdiem->show()  . '<br />' . $objcreateinvtab->show() . '<br />' . $objcreateincident->show();
   $objElement->addTab(array('name'=>'TEV Output to date','content' => $output));
   $objElement->addTab(array('name'=>'Choose a section to complete','content' => $myTab->show()));
     

    echo  $objElement->show();
         
?>
