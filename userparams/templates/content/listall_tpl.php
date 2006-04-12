<?php

if (isset($errorStr)) {
   echo $errorStr;
} else {
    //The URL for the add link
    $addLink=$this->uri(array('action' => 'add'));
    //Create add link
    $objAddLink = &$this->newObject('link', 'htmlelements');
    $objAddLink->link($this->uri(array('action' => 'add')));
    $objAddLink->link = $this->objLanguage->languageText('mod_userparams_add');

    //Get the icon class and create an add, edit and delete instance
    $objAddIcon = $this->newObject('geticon', 'htmlelements');
    $objAddIcon->alt=$this->objLanguage->languageText("mod_userparams_add");
    
    $objEditIcon = $this->newObject('geticon', 'htmlelements');
    $objDelIcon = $this->newObject('geticon', 'htmlelements');

    $userId = $this->objUser->userId();
    $pgTitle =& $this->getObject('htmlheading', 'htmlelements');
    $pgTitle->type = 1;
    $no = count($ar);   
    if($no <=3 ){
      $pgTitle->str = $this->objLanguage->languageText('mod_userparams_title')."&nbsp;"
               .$this->objUser->fullname($userId)."&nbsp;".$objAddIcon->getAddIcon($addLink);
     }else {
         $pgTitle->str = $this->objLanguage->languageText('mod_userparams_title')."&nbsp;"
                      .$this->objUser->fullname($userId);
           }           
    //Create an instance of the table object
    $objTable = $this->newObject('htmltable', 'htmlelements');
    $objTable->border = "0";
    $objTable->cellpadding = "4";
    $objTable->cellspacing = "2";
    //Create a header row
    $objTable->startHeaderRow();
    //Add cells for each heading title
    $objTable->addHeaderCell($this->objLanguage->languageText("mod_userparams_pname"));
    $objTable->addHeaderCell($this->objLanguage->languageText("mod_userparams_pvalue"));
    $objTable->addHeaderCell($this->objLanguage->languageText("phrase_dateadded"));
    $objTable->addHeaderCell($this->objLanguage->languageText("mod_userparams_action"));
    $objTable->endHeaderRow();
    
       
    
    
    //If there are data in the array
    if (isset($ar)) {
        //Initialize the odd/even row counter
        $rowcount=0;
        //Loop over the array
        foreach ($ar as $line) {
            //Check if the row is odd or even
            $oddOrEven=($rowcount==0) ? "odd" : "even";
            //Get the key value
            $id = $line['id'];
            
            //The URL for the edit link
            $editLink=$this->uri(array('action' => 'edit',
              'id' => $id), 'userparams');
            $objEditIcon->alt=$this->objLanguage->languageText("mod_userparams_add");
             
            // The delete icon with link uses confirm delete utility
            $objDelIcon->setIcon("delete");
            $rep = array('PARAM' => $line['pname']);
            $objDelIcon->alt=$this->objLanguage->code2Txt("mod_userparams_delete", $rep);
            $delLink = $this->uri(array(
        	  'action' => 'delete',
        	  'confirm' => 'yes',
              'id' => $id), 'userparams');
            $objConfirm=&$this->newObject('confirm','utilities');
            $rep = array('PARAM', $line['pname']);
            $objConfirm->setConfirm($objDelIcon->show(),
               $delLink,$this->objLanguage->code2Txt("mod_userparams_confirmdelete", $rep));
            $conf = $objConfirm->show();

            
            //Add a row for the data
            $objTable->startRow();
            //Add cell for the parameter name
            $objTable->addCell($line['pname'], NULL, "top", "left", $oddOrEven);
            //Add cell for the parameter value
            $objTable->addCell($line['pvalue'], NULL, "top", "left", $oddOrEven);
            $objTable->addCell($line['dateCreated'], NULL, "top", "left", $oddOrEven);
            $objTable->addCell($objEditIcon->getEditIcon($editLink)."&nbsp;".$conf, NULL, "top", "left", $oddOrEven);
            //End the table row
            $objTable->endRow();
            //Set rowcount for bitwise determination of odd or even
            $rowcount=($rowcount==0) ? 1 : 0;
        }
        $content = "";
        //Show the page title
        $content .= $pgTitle->show();    
        //Show the table
        $content .= $objTable->show();
        
        if (empty($ar)){
        //Show no records message
        $content .="<span class='noRecordsMessage'>".$this->objLanguage->languageText('mod_userparams_nodata')."</span>";
        }
        if($no <=3 ){
        //Show the add link
        $content .= $objAddLink->show();
        }
        echo $content;
    }
} # if no error
?>
