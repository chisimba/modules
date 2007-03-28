<?php
/**create a template for all school list information**/ 

/**
 *load all classes
 */
 $this->loadClass('textinput','htmlelements');
 $this->loadClass('textarea','htmlelements');
 $this->loadclass('button','htmlelements');
/*------------------------------------------------------------------------------*/ 
 
 /**
  *define all language items
  */
     
 $schoolname = $this->objLanguage->languageText('phrase_schoolname');
 $schooladdy  = $this->objLanguage->languageText('phrase_schooladdress');
 $telnumber  = $this->objLanguage->languageText('phrase_telnumber');
 $faxnumber = $this->objLanguage->languageText('phrase_faxnumber');
 $email = $this->objLanguage->languageText('phrase_email');
 $principal = $this->objLanguage->languageText('phrase_principal');
 $guidanceteacher = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteacher','marketingrecruitmentforum');
 $btnNext  = $this->objLanguage->languageText('word_next');
 $str1 = ucfirst($btnNext);
 $schooselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');
 $provinceselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_provinceselect','marketingrecruitmentforum');
 $areaselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_areaselect','marketingrecruitmentforum');
 $schoolselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');
 
 $wcprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_wcprov','marketingrecruitmentforum');
 $ecprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_ecprov','marketingrecruitmentforum');
 $ncprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_ncprov','marketingrecruitmentforum');
 $gprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_gprov','marketingrecruitmentforum');
 $mprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_mprov','marketingrecruitmentforum');
 $lprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_lprov','marketingrecruitmentforum');
 $nwprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_nwprov','marketingrecruitmentforum');
 $fprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_fprov','marketingrecruitmentforum');
 $knprov = $this->objLanguage->languageText('mod_marketingrecruitmentforum_knprov','marketingrecruitmentforum');
 
 $schvalid  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schvalid','marketingrecruitmentforum');
 $telvalid  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_telvalid','marketingrecruitmentforum');
 $faxval = $this->objLanguage->languageText('mod_marketingrecruitmentforum_faxval','marketingrecruitmentforum');
 $emailvalid = $this->objLanguage->languageText('mod_marketingrecruitmentforum_emailvalid','marketingrecruitmentforum');
 $emailformat = $this->objLanguage->languageText('mod_marketingrecruitmentforum_emailformat','marketingrecruitmentforum');
 $province  = $this->objLanguage->languageText('word_province');
 $principalEmailAddy  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_principalemailaddy','marketingrecruitmentforum');
 $prinicipalCellNo  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_principalcellno','marketingrecruitmentforum');
 $guidanceteachemail  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteachemail','marketingrecruitmentforum');
 $guidanceteachcellno1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteachcellno1','marketingrecruitmentforum');
 $selectSch = $this->objLanguage->languageText('mod_marketingrecruitmentforum_selectschool','marketingrecruitmentforum');
 $selectArea  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_selectarea','marketingrecruitmentforum');
 $selectProvince  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_selectprovince','marketingrecruitmentforum');
 $enterPrincipalname = $this->objLanguage->languageText('mod_marketingrecruitmentforum_enterPrincipalname','marketingrecruitmentforum');
 $maxprincename = $this->objLanguage->languageText('mod_marketingrecruitmentforum_maxprincname1','marketingrecruitmentforum');
 $enterGuidanceName = $this->objLanguage->languageText('mod_marketingrecruitmentforum_enterguidancename','marketingrecruitmentforum');
 
/*------------------------------------------------------------------------------*/  
 
  /**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_schoollist11','marketingrecruitmentforum');
  
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type=5;
  $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');
  
/*------------------------------------------------------------------------------*/  
 
 /**
   *create all textinputs
   */
    $this->dbschoollist =& $this->getObject('dbschoollist','marketingrecruitmentforum');
    $schooldata []= $this->getSession('schoolvalues');
    $namevalue = $this->getSession('nameschool');
    $schoolbyname11 = $this->dbschoollist->getschoolbyname($namevalue, $field = 'schoolname', $start = 0, $limit = 0);
    
    $txtschoolname  = ' '; 
    $schooladdress  = ' ';
    $txttelcode = ' ';
    $txttelnumber = ' ';
    $txtfaxnumber = ' ';
    $txtfaxcode = ' ';
    $txtemail = ' ';//school email
    $txtprincipal = ' ';
    $txtteacher = ' ';
    $principalemailaddy = ' ';
    $principalcellno  = ' ';
    $guidanceteacheamil = ' ';
    $guidanceteachcellno  = ' ';
    
  
  if(!empty($schooldata)){
        foreach($schooldata as $sessschool){
        
            $txtschoolname  = $sessschool['schoolname']; 
            $schooladdress  = $sessschool['schooladdress'];
            $txttelcode = $sessschool['telcode'];
            $txttelnumber = $sessschool['telnumber'];
            $txtfaxcode = $sessschool['faxcode'];
            $txtfaxnumber = $sessschool['faxnumber'];
            $txtemail = $sessschool['email'];
            $txtprincipal = $sessschool['principal'];
            $txtteacher = $sessschool['guidanceteacher'];
            $principalemailaddy =$sessschool['principalemail'];
            $principalcellno  = $sessschool['principalCellno'];
            $guidanceteacheamil =$sessschool['guidanceteachemail'];
            $guidanceteachcellno  = $sessschool['guidanceteachcellno'];
        }
   }
  
  if(!empty($schoolbyname11)){
          for($i=0; $i< count($schoolbyname11); $i++){
            $txtschoolname  = $schoolbyname11[$i]->SCHOOLNAME; 
            $schooladdress  = $schoolbyname11[$i]->SCHOOLADDRESS;
            $txttelnumber = $schoolbyname11[$i]->TELNUMBER;
            $txttelcode = $schoolbyname11[$i]->TELCODE;
            $txtfaxcode = $schoolbyname11[$i]->FAXCODE;
            $txtfaxnumber = $schoolbyname11[$i]->FAXNUMBER;
            $txtemail = $schoolbyname11[$i]->EMAIL;
            $txtprincipal = $schoolbyname11[$i]->PRINCIPAL;
            $txtteacher = $schoolbyname11[$i]->GUIDANCETEACHER;
            $principalemailaddy  = $schoolbyname11[$i]->PRINCIPALEMAIL;
            $principalcellno = $schoolbyname11[$i]->PRINCIPALCELLNO;
            $guidanceteacheamil = $schoolbyname11[$i]->GUIDANCETEACHEMAIL;
            $guidanceteachcellno  = $schoolbyname11[$i]->GUIDANCETEACHCELLNO;
         }
  }
/*--------------------------------------------------------------------------------------------*/               

       $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
       $schoolvalues = array();
       $schoolnames = $this->objfaculties->getSchools(); 
       for($i=0; $i < count($schoolnames); $i++){
            $schoolvalues[$i]=$schoolnames[$i]->SCHOOLNAME;
       }

       //create dropdown list
       $searchlist  = new dropdown('schoollistactivity');
       
       sort($schoolvalues);
       foreach($schoolvalues as $sessschool){
          $searchlist->addOption(NULL, ''.$schooselect);
          $searchlist->addOption($sessschool,$sessschool);
       }
/*--------------------------------------------------------------------------------------------*/ 
   /**
     *create dropdown list for all province values
     */       
   $this->objprovincedropdown  = new dropdown('provinceschool');
   $this->objprovincedropdown->addOption(NULL,$provinceselect."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp") ;
   $this->objprovincedropdown->addOption($wcprov,$wcprov) ;
   $this->objprovincedropdown->addOption($ecprov,$ecprov) ;
   $this->objprovincedropdown->addOption($ncprov,$ncprov) ;
   $this->objprovincedropdown->addOption($gprov,$gprov) ;
   $this->objprovincedropdown->addOption($mprov,$mprov) ;
   $this->objprovincedropdown->addOption($lprov,$lprov) ;
   $this->objprovincedropdown->addOption($nwprov,$nwprov) ;
   $this->objprovincedropdown->addOption($fprov,$fprov) ;
   $this->objprovincedropdown->addOption($knprov,$knprov) ;

/*--------------------------------------------------------------------------------------------*/       
/**
 *create a dropdown list with all area values
 */
     $postAreaInfo = $this->objfaculties->getPostInfo(); 
       for($i=0; $i < count($postAreaInfo); $i++){
            $areavals[$i]=$postAreaInfo[$i]->CITY;
       }
       //create dropdown list
       $arealist  = new dropdown('areaschool');
       sort($areavals);
       foreach($areavals as $sessarea){
          $arealist->addOption(NULL, " ".$areaselect."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp");
          $arealist->addOption($sessarea,$sessarea);
       }  
/*--------------------------------------------------------------------------------------------*/ 
  /**
   *create all textboxes
   */     
   $textArea = 'schooladdress';
   $this->objSchooladdress = new textArea('schooladdress');
   $this->objSchooladdress->setRows(3);
   $this->objSchooladdress->setColumns(48);
   $this->objSchooladdress->setContent($schooladdress);
   
   $this->objtxttelcode = new textinput("txttelcode");
   $this->objtxttelcode->value  = $txttelcode;
   $this->objtxttelcode->size = 3;
   
   $this->objtxtfaxcode = new textinput("txtfaxcode");
   $this->objtxtfaxcode->value  = $txtfaxcode;
   $this->objtxtfaxcode->size = 3;
   
   $this->objtxttelnumber = new textinput("txttelnumber");
   $this->objtxttelnumber->value  = $txttelnumber;
   $this->objtxttelnumber->size = 26;
   
   $this->objtxtfaxnumber = new textinput("txtfaxnumber");
   $this->objtxtfaxnumber->value  = $txtfaxnumber;
   $this->objtxtfaxnumber->size = 26;
   
   $this->objtxtemail = new textinput("txtemail");
   $this->objtxtemail->value  = $txtemail;
   $this->objtxtemail->size = 35;
   
   $this->objtxtprincipal = new textinput("txtprincipal");
   $this->objtxtprincipal->value  = $txtprincipal;
   $this->objtxtprincipal->size = 35;
   
   $this->objtxtPrincEmailAddy = new textinput("txtprincemailaddy");
   $this->objtxtPrincEmailAddy->value  = $principalemailaddy;
   $this->objtxtPrincEmailAddy->size = 35;
   
   $this->objtxtPrincCellNo = new textinput("txtprinccellno");
   $this->objtxtPrincCellNo->value  = $principalcellno;
   $this->objtxtPrincCellNo->size = 35;
   
   $this->objtxtteacher = new textinput("txtteacher");
   $this->objtxtteacher->value  = $txtteacher;
   $this->objtxtteacher->size = 35;
   
   $this->objtxtTeacherEmail = new textinput("txtteacheremail");
   $this->objtxtTeacherEmail->value  = $guidanceteacheamil;
   $this->objtxtTeacherEmail->size = 35;
   
   $this->objtxtTeacherCellNo = new textinput("txtteachercellno");
   $this->objtxtTeacherCellNo->value  = $guidanceteachcellno;
   $this->objtxtTeacherCellNo->size = 35;

/*------------------------------------------------------------------------------*/   
   /**
     *create a next button
     */
    $this->objButtonNext  = new button('schoolnext', $str1);
    $this->objButtonNext->setToSubmit();

    /**
     * get the schoolname selected from session variable
     */        
     $nameselected  = $this->getSession('nameschool');
/*------------------------------------------------------------------------------*/   
  /**
   *create a table to place all form elements in
   */
   
    $myTable=$this->newObject('htmltable','htmlelements');
    $myTable->width='80%';
    $myTable->border='0';
    $myTable->cellspacing='4';
    $myTable->cellpadding='10';
           
    $myTable->startRow();
    $myTable->addCell(ucfirst($schoolname));
    $myTable->addCell('<h3>'.$nameselected.'<h3/>');
    $myTable->endRow();   
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($schooladdy));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objSchooladdress->show());
    $myTable->endRow(); 
    
    $myTable->startRow();
    $myTable->addCell(ucfirst('Area / Town'));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$arealist->show());
    $myTable->endRow(); 
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($province));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objprovincedropdown->show());
    $myTable->endRow();
 
    $myTable->startRow();
    $myTable->addCell(ucfirst($telnumber));
    $myTable->addCell("<span class=error>" .'<b>'.'&nbsp'."</span>".'</b>'.' '.$this->objtxttelcode->show().' '.$this->objtxttelnumber->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($faxnumber));
    $myTable->addCell("<span class=error>" .'<b>'.'&nbsp'."</span>".'</b>'.' '.$this->objtxtfaxcode->show().' '.$this->objtxtfaxnumber->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($email));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtemail->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($principal));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtprincipal->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell($principalEmailAddy);
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtPrincEmailAddy->show());
    $myTable->endRow(); 
    
    $myTable->startRow();
    $myTable->addCell($prinicipalCellNo);
    $myTable->addCell("&nbsp"."&nbsp".$this->objtxtPrincCellNo->show());
    $myTable->endRow(); 
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($guidanceteacher));
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtteacher->show());
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell($guidanceteachemail);
    $myTable->addCell("&nbsp"."&nbsp".$this->objtxtTeacherEmail->show());
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell($guidanceteachcellno1);
    $myTable->addCell("&nbsp"."&nbsp".$this->objtxtTeacherCellNo->show());
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell($this->objButtonNext->show());
    $myTable->endRow();    

/*------------------------------------------------------------------------------*/    
    
  /**
   *create a form to place all elements in
   */
   
   $objForm = new form('schoollist',$this->uri(array('action'=>'schooleditoutput')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>".'<br />'.$myTable->show());
  // $objForm->addRule('schoollistactivity',$selectSch,'required');
   $objForm->addRule('schooladdress',$schvalid,'required');
   $objForm->addRule('areaschool',$selectArea,'required');
   $objForm->addRule('provinceschool',$selectProvince,'required');
   //$objForm->addRule('txttelnumber',$telvalid,'required');
   //$objForm->addRule('txtfaxnumber',$faxval,'required');
   $objForm->addRule('txtemail',$emailvalid,'required');
   $objForm->addRule('txtemail',$emailformat,'email');
   $objForm->addRule('txtprincipal',$enterPrincipalname,'required');
   $objForm->addRule(array('name'=>'txtprincipal','length'=>45),$maxprincename, 'maxlength');
   $objForm->addRule('txtprincemailaddy',$emailvalid,'required');
   $objForm->addRule('txtprincemailaddy',$emailformat,'email');
   $objForm->addRule('txtteacher',$enterGuidanceName,'required');
   $objForm->addRule(array('name'=>'txtteacher','length'=>45),$maxprincename, 'maxlength');
   
/*------------------------------------------------------------------------------*/   
          
   /**
     *display the schoolist interface
     */
                                
   echo  $objForm->show();	          
  
?>
