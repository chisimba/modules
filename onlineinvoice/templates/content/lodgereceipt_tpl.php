<?php

  /**
   *create a template for uploading the lodge receipts
   */
   
   /*create template for lodging expenses*/
    $this->objlodgeHeading = $this->newObject('htmlheading','htmlelements');
    $this->objlodgeHeading->type = 2;
    $this->objlodgeHeading->str=$objLanguage->languageText('mod_onlineinvoice_travellodgingexpenses','onlineinvoice');
    
    /**
     *create all language items
     */
     $lodgeExpenditures = $this->objLanguage->languageText('mod_onlineinvoice_itemizedexpenditures','onlineinvoice');
     $receipt  = $this->objLanguage->languageText('mod_onlineinvoice_uploadreceipts','onlineinvoice');
     $next = ucfirst($this->objLanguage->languageText('phrase_next'));
     $exit = ucfirst($this->objLanguage->languageText('phrase_exit'));
     $back = ucfirst($this->objLanguage->languageText('word_back'));

     
/*********************************************************************************************************************************************************************/     
     /**
      *create all text inputs
      */
      
   //$this->objtxtquotesource  = new textinput('txtquotesource', ' ','text');
   //$this->objtxtquotesource->id = 'txtquotesource';
   
   $this->loadClass('textinput', 'htmlelements');
   $this->objtxtfilereceipt = new textinput('upload',' ','FILE');
   $this->objtxtfilereceipt->id = 'txtfilereceipt';

   $this->objtxtcreateaffidavit  = new textinput('txtcreateaffidavit', ' ','text');
   $this->objtxtcreateaffidavit->id = 'txtcreateaffidavit';
   
/*********************************************************************************************************************************************************************/   
   /*create all button elements*/


  $this->loadclass('button','htmlelements');
  $this->objnext  = new button('next', $next);
  $this->objnext->setToSubmit();

  $this->objexit  = new button('exit', $exit);
  $this->objexit->setToSubmit();

  $this->objBack  = new button('back', $back);
  $this->objBack->setToSubmit();        
     
/*********************************************************************************************************************************************************************/


/*create table for receipt information*/        

        $myTabReceipt  = $this->newObject('htmltable','htmlelements');
        $myTabReceipt->width='75%';
        $myTabReceipt->border='0';
        $myTabReceipt->cellspacing = '10';
        $myTabReceipt->cellpadding ='10';
        
        
        $myTabReceipt->startRow();
        $myTabReceipt->addCell('Upload Receipt');
        $myTabReceipt->addCell($this->objtxtfilereceipt->show());
        $myTabReceipt->endRow();
        
        $myTabReceipt->startRow();
        $myTabReceipt->addCell('Create an Affidavit');
        $myTabReceipt->addCell($this->objtxtcreateaffidavit->show());
        $myTabReceipt->endRow();
        
        $myTabReceipt->startRow();
        $myTabReceipt->addCell(' ');
//        $myTabReceipt->addCell($this->objButtonUploadReceipt->show());
        $myTabReceipt->endRow();
        
        $myTabReceipt->startRow();
        $myTabReceipt->endRow();
        
        

/*********************************************************************************************************************************************************************/         
      /**
       *create a tabbed box to place table and elements in
       */
      $this->loadClass('tabbedbox', 'htmlelements'); 
      $objtabreceipt = new tabbedbox();
      $objtabreceipt->addTabLabel('Receipt Information');
      $objtabreceipt->addBoxContent('<br>'  . '<b />' .$receipt  . '<br>' ."<div align=\"left\">"  .$myTabReceipt->show());
                    
 
/*********************************************************************************************************************************************************************/ 
    /**
     *create a form to place all elements in
     */
     
     $objLodgeReceipt = new form('lodging',$this->uri(array('action'=>'submitlodgereceipt')));
     $objLodgeReceipt->displayType = 3;
     $objLodgeReceipt->addToForm('<br> />'.$objtabreceipt->show().'<br />' . "<div align=\"center\">" . $this->objBack->show(). $this->objnext->show() . ' ' . $this->objexit->show()."</div");
     $objLodgeReceipt->addRule('upload','You have to insert a file','required');	
     $objLodgeReceipt->extra="enctype='multipart/form-data'";      
/*********************************************************************************************************************************************************************/     
     echo "<div align=\"center\">" . $this->objlodgeHeading->show()  . "</div>";
     echo "<div align=\"center\">"."<div class=\"error\">" .'<br>'  . $lodgeExpenditures . "</div>";
     echo  "<div align=\"left\">"  . $objLodgeReceipt->show() . "</div";   
?>
