<?php
    /**
     *create a template display the output for claimant details and itinerary information
     */
     
     /**
      *load all classes
      */
      
      $this->loadClass('htmlheading', 'htmlelements');
      $this->loadClass('label','htmlelements');
      $this->loadClass('form','htmlelements');
      $this->loadClass('htmltable','htmlelements');
      $this->loadClass('tabbedbox', 'htmlelements');
      $this->loadClass('button','htmlelements');
       //$heading = $this->new
/****************************************************************************************************************************/      
      /**
       *define all language elements
       */
       
       $expensesheet  = $this->objLanguage->languageText('mod_onlineinvoice_travelsheet','onlineinvoice');
       $mainheading = $this->objLanguage->languageText('mod_onlineinvoice_claimantdetails','onlineinvoice');  
       $beginDate = $objLanguage->languageText('phrase_begindate');
       $endDate  = $objLanguage->languageText('phrase_enddate');
       $name = $this->objLanguage->languageText('phrase_claimantname');
       $title  = $this->objLanguage->languageText('phrase_claimanttitle');
       $address  = $this->objLanguage->languageText('phrase_mailingaddress');
       $city = $this->objLanguage->languageText('word_city');
       $province = $this->objLanguage->languageText('word_province');
       $postalcode = $this->objLanguage->languageText('phrase_postalcode');
       $country  = $this->objLanguage->languageText('word_country');
       $travelpurpose = $this->objLanguage->languageText('mod_onlineinvoice_travelpurpose','onlineinvoice');
       $btnEdit  = $this->objLanguage->languageText('word_edit');
       $btnSave = $this->objLanguage->languageText('word_save');
       /********************************************************************/
       $deptDate  = $this->objLanguage->languageText('phrase_departuredate');
       $str1  = strtoupper($deptDate);
       $deptTime  = $this->objLanguage->languageText('phrase_departuretime');
       $str2  = strtoupper($deptTime);
       $deptCity  = $this->objLanguage->languageText('phrase_departurecity');
       $str3  = strtoupper($deptCity);
       $arrivalDate  = $this->objLanguage->languageText('phrase_arrivaldate');
       $str4  = strtoupper($arrivalDate);
       $arrivalTime  = $this->objLanguage->languageText('phrase_arrivaltime');
       $str5  = strtoupper($arrivalTime);
       $arrivalCity  = $this->objLanguage->languageText('phrase_arrivalcity');
       $str6  = strtoupper($arrivalCity);
       /**********************************************************************/
       $expensesdate = $this->objLanguage->languageText('word_date');
       $strdate = strtoupper($expensesdate);
       $breakfast  = $this->objLanguage->languageText('word_breakfast');
       $strbreakfast  = strtoupper($breakfast);
       $lunch  = $this->objLanguage->languageText('word_lunch');
       $strlunch  = strtoupper($lunch);
       $dinner = $this->objLanguage->languageText('word_dinner');
       $strdinner = strtoupper($dinner);
       //$location = $this->objLanguage->languageText('word_location');
    
       
/****************************************************************************************************************************/       
       /**
        *create all heading elements
        */    
        
         $strheading  = strtoupper($expensesheet);
         $objtravelsheet  = new htmlHeading();
         $objtravelsheet->type  = 1;
         $objtravelsheet->str = $strheading;
        
         $str = strtoupper($mainheading); 
         $objoutputheading  = new htmlHeading();  
         $objoutputheading->type = 4;
         $objoutputheading->str = $str;
/****************************************************************************************************************************/
      /**
       *create button elements
       */

       
       $objeditbutton  = new button('edit', $btnEdit);
       $objeditbutton->setToSubmit();
    
       $objsavebutton  = new button('save', $btnSave);
       $objsavebutton->setToSubmit();
       

       
                    
/****************************************************************************************************************************/
         /**
          *call the session variable that contains array of information entered by the user
          *assign the array session to a variable $dateinfo
          *loop through the array variable and assign each element to a variable                    
          */
          $dateinfo     =  $this->getSession('invoicedata');
          while (list ($count, $values)  = each ($dateinfo)) {
              if($count == 'begindate') {
              $bdate = $values;
              }
              if($count ==  'enddate') {
              $edate  = $values;
              }
          }
/****************************************************************************************************************************/
         /**
          *call the session variable that contains array of information entered by the user
          *assign the array session to a variable $claimantinfo
          *loop through the array variable and assign each element to a variable                    
          */   
         //$claimantinfo  = array();                       
         $claimantinfo = $this->getSession('claimantdata');
           while (list ($key, $val)  = each ($claimantinfo)) {
              if($key == 'claimanantname') {
              $n = strtoupper($val);
              }
              if($key ==  'title') {
              $t  = strtoupper($val);
              }
              if($key ==  'mailaddress')  {
              $a  = strtoupper($val);
              }
              if($key ==  'city')  {
              $ct = strtoupper($val);
              }
              if($key ==  'province')  {
              $p  = strtoupper($val);
              }
              if($key ==  'postalcode')  {
              $po = strtoupper($val);
              }
              if($key ==  'country')  {
              $coun = strtoupper($val);
              }
              if($key ==  'travelpurpose') {
              $purpose  = strtoupper($val);
              }
              
                 
           }
           
             
/****************************************************************************************************************************/
        /**
         *call the session variable that contains array of information entered by the user
         *assign the array session to a variable $itineraryinfo
         *loop through the array variable and assign each element to a variable
         */         
         $itinerarydetails  = $this->getSession('addmultiitinerary');
            while(list($start,$result)  = each($itinerarydetails)) {
              if($start == 'departuredate') {
              $displaydepdate = $result;
              }
              if($start == 'departuretime') {
              $displaydepttime == $result;
              }
              if($start == 'departurecity') {
              $displaydeptcity == $result;
              }
              if($start == 'arrivaledate') {
              $displayarrivdate = $result;
              }
              if($start == 'arrivaltime') {
              $displayarrivtime = $result;
              }
              if($start == 'arrivalcity') {
              $displayarrivcity = $result;
              }
              
            
            }                                  
/****************************************************************************************************************************/                 
        /**
         *create a table to place all claimant form elements in
         */ 
         
         $myTable =  new htmlTable;      
         $myTable->width='70%';
         $myTable->border='0';
         $myTable->cellspacing = '10';
         $myTable->cellpadding ='10';

         $myTable->startRow();
         $myTable->addCell($beginDate);
         $myTable->addCell($bdate);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($endDate);
         $myTable->addCell($edate);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($name);
         $myTable->addCell($n);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($title);
         $myTable->addCell($t);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($address);
         $myTable->addCell($a);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($city);
         $myTable->addCell($ct);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($province);
         $myTable->addCell($p);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($postalcode);
         $myTable->addCell($po);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($country);
         $myTable->addCell($coun);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($travelpurpose);
         $myTable->addCell($purpose);
         $myTable->endRow();
         
         /**
          *create a table for form buttons
          */                    
        
         $myTabbuttons =  new htmlTable;      
         $myTabbuttons->width='20%';
         $myTabbuttons->border='0';
         $myTabbuttons->cellspacing = '10';
         $myTabbuttons->cellpadding ='10';
         
         $myTabbuttons->startRow();
         $myTabbuttons->addCell($objsavebutton->show());
         $myTabbuttons->addCell($objeditbutton->show());
         $myTabbuttons->endRow();
         
        /**
         *create a table to place all itinerary elements in
         */                  
         $myTabItinerary =  new htmlTable;      
         $myTabItinerary->width='100%';
         $myTabItinerary->border='0';
         $myTabItinerary->cellspacing = '30';
         $myTabItinerary->cellpadding ='10';

         $myTabItinerary->startRow();
         $myTabItinerary->addCell($str1);
         $myTabItinerary->addCell($str2);
         $myTabItinerary->addCell($str3);
         $myTabItinerary->addCell($str4);
         $myTabItinerary->addCell($str5);
         $myTabItinerary->addCell($str6);
         $myTabItinerary->endRow();
         
         $myTabItinerary->startRow();
         $myTabItinerary->addCell($displaydepdate);
         $myTabItinerary->addCell($displaydepttime);
         $myTabItinerary->addCell($displaydeptcity);
         $myTabItinerary->addCell($displayarrivdate);
         $myTabItinerary->addCell($displayarrivtime);
         $myTabItinerary->addCell($displayarrivcity);
         $myTabItinerary->endRow();
         
         /**
          *create a table to place per diem expenses in
          */                   
          
         $myTabExpense =  new htmlTable;      
         $myTabExpense->width='100%';
         $myTabExpense->border='0';
         $myTabExpense->cellspacing = '30';
         $myTabItinerary->cellpadding ='10';

         $myTabExpense->startRow();
         $myTabExpense->addCell($strdate);
         $myTabExpense->addCell($strbreakfast);
         $myTabExpense->addCell($strlunch);
         $myTabExpense->addCell($strdinner);
         $myTabExpense->endRow();
         
         $myTabExpense->startRow();
         $myTabExpense->addCell('');
         $myTabExpense->addCell('Location');
         $myTabExpense->addCell('Rate');
         $myTabExpense->addCell('Location');
         $myTabExpense->addCell('Rate');
         $myTabExpense->addCell('Location');
          $myTabExpense->addCell('Rate');
         $myTabExpense->endRow(); 
/****************************************************************************************************************************/
        /**
         *create a tabbed box element
         */
         
         $objtabbedbox = new tabbedbox();
         $objtabbedbox->addTabLabel('Claimant Information');
         $objtabbedbox->addBoxContent($myTable->show());        
         
         $objtabItinerary = new tabbedbox();
         $objtabItinerary->addTabLabel('Itinerary Information');
         $objtabItinerary->addBoxContent($myTabItinerary->show());        
         
         $objtabExpense = new tabbedbox();
         $objtabExpense->addTabLabel('Per Diem Expenses');
         $objtabExpense->addBoxContent($myTabExpense->show());
               
/****************************************************************************************************************************/
                                
        /**
         *create a form element
         */                        
                  
        $objClaimantForm = new form('claimantoutput',$this->uri(array('action'=>'savealldetails')));
        $objClaimantForm->displayType = 3;
        $objClaimantForm->addToForm($objtabbedbox->show() . '<br>'  . $objtabItinerary->show() .  '<br>' . $myTabbuttons->show() . '<br>'  .$objtabExpense->show());	
        //$objClaimantForm->addRule('txtDate', 'Must be number','required');             

/****************************************************************************************************************************/
        /**
         *display output to screen
         */
         echo  "<div align=\"center\">" . $objtravelsheet->show() . "</div>";
         echo  '<br>' . '<br>'  . $objoutputheading->show();
         echo  '<br>' . '<br>'  . $objClaimantForm->show();   
        // echo '<br>'  .                  
                                             

?>
