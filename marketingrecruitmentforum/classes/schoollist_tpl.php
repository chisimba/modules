<?php
/**
 *create a template for all school lists
 */ 
/*------------------------------------------------------------------------------*/

/**
 *load all classes
 */
 $this->loadClass('textinput','htmlelements');
 $this->loadClass('textarea','htmlelements');
 $this->loadclass('button','htmlelements');

/*------------------------------------------------------------------------------*/ 
 
 /**
  *all language items
  */
     
 $schoolname = $this->objLanguage->languageText('phrase_schoolname');
 $schooladdy  = $this->objLanguage->languageText('phrase_schooladdress');
 $telnumber  = $this->objLanguage->languageText('phrase_telnumber');
 $faxnumber = $this->objLanguage->languageText('phrase_faxnumber');
 $email = $this->objLanguage->languageText('word_email');
 $principal = $this->objLanguage->languageText('word_principal');
 $guidanceteacher = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteacher','marketingrecruitmentforum');
 $btnNext  = $this->objLanguage->languageText('word_next');
 $str1 = ucfirst($btnNext);

/*------------------------------------------------------------------------------*/  
 
  /**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_schoollist','marketingrecruitmentforum');
  
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type=5;
  $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');
  
/*------------------------------------------------------------------------------*/  
 
 /**
   *create all textinputs
   */
    $schooldata []= $this->getSession('schoolistvalues');
    $txtschoolname  = ' '; 
    $schooladdress  = ' ';
    $txttelnumber = ' ';
    $txtfaxnumber = ' ';
    $txtemail = ' ';
    $txtprincipal = ' ';
    $txtteacher = ' ';
  
  if(!empty($schooldata)){
    foreach($schooldata as $sessschool){
    
        $txtschoolname  = $sessschool['schoolname']; 
        $schooladdress  = $sessschool['schooladdress'];
        $txttelnumber = $sessschool['telnumber'];
        $txtfaxnumber = $sessschool['faxnumber'];
        $txtemail = $sessschool['email'];
        $txtprincipal = $sessschool['principal'];
        $txtteacher = $sessschool['guidanceteacher'];
    
    }
  }
   
//   $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
//   $values  = $this->objschoolname->readfiledata();

/*--------------------------------------------------------------------------------------------*/               
       //create an object of the schoolnames class
       //call the function that sets the session
       //call the session
       //populate list with values in the session array 
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       $this->objschoolname->readfiledata();
        
       $searchlist  = new dropdown('schoollistactivity');
       $shoolvalues = $this->getSession('schoolnames');
       sort($shoolvalues);
       foreach($shoolvalues as $sessschool){
          
          $searchlist->addOption($sessschool,$sessschool);
       }
       
/*--------------------------------------------------------------------------------------------*/       

        
  /* $this->objtxtschoolname = $this->newObject('textinput','htmlelements');      //change rem    
   $this->objtxtschoolname->name   = "txtschoolname";
   $this->objtxtschoolname->value  = $txtschoolname;*/
  
   $textArea = 'schooladdress';
   $this->objSchooladdress =& $this->newobject('textArea','htmlelements');
   $this->objSchooladdress->setRows(1);
   $this->objSchooladdress->setColumns(15);
   $this->objSchooladdress->setName($textArea);
   $this->objSchooladdress->setContent($schooladdress);
   
   $this->objtxttelnumber = $this->newObject('textinput','htmlelements'); 
   $this->objtxttelnumber->name  = "txttelnumber";
   $this->objtxttelnumber->value  = $txttelnumber;
   
   $this->objtxtfaxnumber = $this->newObject('textinput','htmlelements'); 
   $this->objtxtfaxnumber->name  = "txtfaxnumber";
   $this->objtxtfaxnumber->value  = $txtfaxnumber;
   
   $this->objtxtemail = $this->newObject('textinput','htmlelements'); 
   $this->objtxtemail->name  = "txtemail";
   $this->objtxtemail->value  = $txtemail;
   
   $this->objtxtprincipal = $this->newObject('textinput','htmlelements'); 
   $this->objtxtprincipal->name  = "txtprincipal";
   $this->objtxtprincipal->value  = $txtprincipal;
   
   $this->objtxtteacher = $this->newObject('textinput','htmlelements'); 
   $this->objtxtteacher->name  = "txtteacher";
   $this->objtxtteacher->value  = $txtteacher;

/*------------------------------------------------------------------------------*/   
   /**
     *create a next button
     */
    $this->objButtonNext  = new button('schoolnext', $str1);
    $this->objButtonNext->setToSubmit();

/*------------------------------------------------------------------------------*/   
  /**
   *create a table to place all form elements in
   */
   
    $myTable=$this->newObject('htmltable','htmlelements');
    $myTable->width='80%';
    $myTable->border='0';
    $myTable->cellspacing='2';
    $myTable->cellpadding='10';
           
    $myTable->startRow();
    $myTable->addCell(ucfirst($schoolname));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$searchlist->show());
    $myTable->endRow();   
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($schooladdy));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objSchooladdress->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($telnumber));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxttelnumber->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($faxnumber));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtfaxnumber->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($email));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtemail->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($principal));
    $myTable->addCell("&nbsp"."&nbsp".$this->objtxtprincipal->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($guidanceteacher));
    $myTable->addCell("&nbsp"."&nbsp".$this->objtxtteacher->show());
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell($this->objButtonNext->show());
    $myTable->endRow();    

/*------------------------------------------------------------------------------*/    
    
  /**
   *create a form to place all elements in
   */
   
   $objForm = new form('schoollist',$this->uri(array('action'=>'showoutput')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>".'<br />'.$myTable->show());
  // $objForm->addRule('schoollistactivity','Please select a school from the list','required');
   $objForm->addRule('schooladdress','Please enter school address','required');
   $objForm->addRule('txttelnumber','Please enter telephone number','required');
   $objForm->addRule('txtfaxnumber','Please enter fax number','required');
   $objForm->addRule('txtemail','Please enter an email address','required');
/*------------------------------------------------------------------------------*/   
          
   /**
     *display the schoolist interface
     */
                                
   echo  $objForm->show();	          
  
?>
