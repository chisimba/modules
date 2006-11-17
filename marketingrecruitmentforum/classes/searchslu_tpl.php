<?php
//template used to display all search facilities for SLU Activities
      
      /**
       *load all classes
       */
       $this->loadClass('dropdown', 'htmlelements');
       $this->loadclass('button','htmlelements');
       
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $searchlist  = new dropdown('searchlist');
       
       $Studcardinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
       $Studcardinfo->tabName = 'OutputInfo';
       
/*---------------------------------------------------------------------------------------------------*/       
                    
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_infocardslu','marketingrecruitmentforum');
/*---------------------------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $searchmsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinstruction','marketingrecruitmentforum');
        $go = $this->objLanguage->languageText('word_go');

/*---------------------------------------------------------------------------------------------------*/        
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('go', $go);
      $this->objButtonGo->setToSubmit();
/*---------------------------------------------------------------------------------------------------*/
      /**
       *create a dropdownlist
       */  
       $this->objsearchinfo->slusearchlist();
       $searchstudslu = $this->getSession('searchstuddata'); 
          
      foreach($searchstudslu as $sesstud){
           $searchlist->addOption($sesstud,$sesstud);
      }
        //return $searchlist->show();
/*---------------------------------------------------------------------------------------------------*/        
        
      /**
       *create a tabbed box to show search results
       */
       $completedcardresults = $this->getSession('searchstuddata');

       if(!empty($completedcardresults)){
              
             //Create table to display dates in session and the rates for breakfast, lunch and dinner and the total rate 
             $objstudsearchTable =& $this->newObject('htmltable', 'htmlelements');
             $objstudsearchTable->cellspacing = '1';
             $objstudsearchTable->cellpadding = '2';
             $objstudsearchTable->border='1';
             $objstudsearchTable->width = '100%';
             $objstudsearchTable->cssClass = 'webfx-tab-style-sheet';
             $objstudsearchTable->footing = 'Please submit or edit information';
  
             $objstudsearchTable->startHeaderRow();
             $objstudsearchTable->addHeaderCell('Name');
             
             $objstudsearchTable->addHeaderCell('Date' );          
             $objstudsearchTable->addHeaderCell('School Name' );
             $objstudsearchTable->addHeaderCell('Surname');
             $objstudsearchTable->addHeaderCell('Name');
             $objstudsearchTable->addHeaderCell('Postal Address');
             $objstudsearchTable->addHeaderCell('Postal Code');
             $objstudsearchTable->addHeaderCell('Telephone Number');
             $objstudsearchTable->addHeaderCell('Telephone code');
             $objstudsearchTable->addHeaderCell('Exemption');
             $objstudsearchTable->addHeaderCell('Faculty');
             $objstudsearchTable->addHeaderCell('Interested Course');
             $objstudsearchTable->addHeaderCell('Relevant Subject');
             $objstudsearchTable->addHeaderCell('SD Case');
             
              $rowcount = '0';
  
             foreach($completedcardresults as $sesstudcardcomp){
     
            $oddOrEven = ($rowcount == 0) ? "odd" : "even";
     
            $objstudsearchTable->startRow();
            $objstudsearchTable->addCell($sesstudcardcomp['date'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['schoolname'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['surname'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['name'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['postaddress'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['postcode'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['telnumber'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['telcode'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['exemption'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['faculty'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['course'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['relevantsubject'], '', '', '', $oddOrEven);
            $objstudsearchTable->addCell($sesstudcardcomp['sdcase'], '', '', '', $oddOrEven);
            
            $objstudsearchTable->endRow();
  }

       $Studcardinfo->addTab('studcardsearch', 'All matriculants that completed information cards', $objstudsearchTable->show());   
       }
       
                    
/*---------------------------------------------------------------------------------------------------*/
 /**
   *create a form to place all elements in
   */
  // $val  = $this->objsearchinfo->slusearchlist();
   $objForm = new form('searchslu',$this->uri(array('action'=>'displaysearch')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'. $searchmsg . ' ' . $searchlist->show() . ' ' .$this->objButtonGo->show());

/*------------------------------------------------------------------------------*
       /**
        *display all information to the screen
        */                       
        /*$val  = $this->objsearchinfo->slusearchlist();
        echo $this->objMainheading->show();
        echo '<br />'  . '<br />';
        echo '<b>' . $searchmsg . '</b>';
        echo ' ' .   $val;*/
        echo $objForm->show();

?>
