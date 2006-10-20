<?php
//template that displays all students that completed student information cards

    /**
     *load all form classes
     */
     $this->loadClass('textinput','htmlelements');
     $this->loadClass('textarea','htmlelements');
     $this->loadclass('button','htmlelements');
     
     $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/
    /**
     *create form heading
     */
     $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
     $this->objMainheading->type=1;
     $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_cardcompheadingschoollist','marketingrecruitmentforum');   
/*------------------------------------------------------------------------------*/
    /**
     *create table to display studcard results
     */
     $shoolname = '';
     $surname = '';
     $name = '';
     $postaddy = '';
     $postcode = '';
     $telnumber = '';
     $telcode = '';
     $results = $this->objstudcard->getallstudinfo();
     //var_dump($results);
     //die;
     
     foreach($results as $sessCard){
          //$myTable
          $shoolname  = $sessCard['schoolname'];
          $surname  = $sessCard['surname'];
          $name  = $sessCard['name'];
          $postaddy  = $sessCard['postaddress'];
          $postcode  = $sessCard['postcode'];
          $telnumber  = $sessCard['telnumber'];
          $telcode  = $sessCard['telcode'];
        //}
     }
     
     $values = $shoolname . '<br /> ' . $surname . '<br />' .$name . '<br />' . $postaddy . '<br />' . $postcode. '<br />' .$telnumber . '<br />'. $telcode;
     
     
/*------------------------------------------------------------------------------*/
    /**
     *display all info on screen
     */                            
     echo    $this->objMainheading->show(); 
     echo $values;                
?>
