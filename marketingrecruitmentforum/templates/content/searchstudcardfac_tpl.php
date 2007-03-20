<?php
    //template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       //$this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objsearchfac  = & $this->newObject('searchfaculty','marketingrecruitmentforum');
       $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
       
/*---------------------------------------------------------------------------------------------------*/       
                    
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_infocardfaculty2','marketingrecruitmentforum');
/*---------------------------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $searchmsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinstruction1','marketingrecruitmentforum');
        $facultyselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum');

/*---------------------------------------------------------------------------------------------------*/   
  	  //call functions to the retrieve the faculty values an populate within dropdown list
    	$faculty = $this->objfaculties->getFaculties();

      //store faculty values into an array
        for($i=0; $i < count($faculty); $i++){
                  $facVAL[$i]=$faculty[$i]->NAME;
        }
      
      //create dropdown for faculty values
    	$objDropdown = new dropdown('facultynameval');  
      sort($facVAL);   
      foreach($facVAL as $sessf){
          $objDropdown->addOption(NULL, ''.$facultyselect); 
          $objDropdown->addOption($sessf,$sessf); 
      }
       
       $objDropdown->extra = ' onChange="document.searchsluresults.submit()"'; 
       $objDropdown->setSelected($this->getParam('facultynameval',NULL));        
        
/*---------------------------------------------------------------------------------------------------*/
     /**
      *call all class objects to define layout
      */
      
      $facultyentered = $this->objsearchfac->studentsbyfaculty($facultyval);
     //var_dump($facultyentered);
      $exemptionfaculty = $this->objsearchfac->exemptionbyfaculty($facultyexmp);
      $facrelsubj = $this->objsearchfac->relsubjbyfaculty($facsubj);
      $coursefaculty  = $this->objsearchfac->coursebyfaculty($faccourse);
      $sdcasefac  = $this->objsearchfac->sdcasebyfaculty($facsdcase);
     
/*---------------------------------------------------------------------------------------------------*/      
     /**
     *create a tabpane to display data within
     */           
    
      $facultyinfo = & $this->newObject('tabcontent','htmlelements');
      $facultyinfo->name = 'studfacdata';
      $facultyinfo->width = '500px';
      
      $facultyinfo->addTab('Students Entered For Faculty',$facultyentered, false);
      $facultyinfo->addTab('Exemption',$exemptionfaculty, false);
      //$facultyinfo->addTab('Subjects',$facrelsubj. '<br />');
      $facultyinfo->addTab('Faculty & Course',$coursefaculty, false);
      $facultyinfo->addTab('SD Cases',$sdcasefac, false);
/*---------------------------------------------------------------------------------------------------*/    
 
    /**
     *create a form to place all elements in
     */
      
     $objForm = new form('searchsluresults',$this->uri(array('action'=>'studcardfaculty')));
     $objForm->displayType = 3;
     $objForm->addToForm("<center>".$this->objMainheading->show(). '<br />' . '<br />'. '<b>' .$searchmsg . "&nbsp" .'</b>'. $objDropdown->show()."</center>" . '<br />' . '<br />' .$facultyinfo->show());

/*---------------------------------------------------------------------------------------------------*/ 
      
     echo $objForm->show();
?>
