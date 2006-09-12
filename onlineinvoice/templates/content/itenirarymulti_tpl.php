<?php

    /************************************************************************
     *                  to do list                                          *
     * 1)either change save button to next -- save session variable in next action
     * 1.1 then add another leg in add button as normal multi array tho into session
     * 2)else get code for both add and save the same -- instrcut user SOMEHOW not to save after adding another day
     * check that all text elements within the form is in reg file   
     * code sytax for next,exit,add another itinerary buttons 
     * ???get multi itinerary button to work
     * take validation out of the exit button                 
     * /    
                                               
    /**
     *create template for multiple / single itinerary 
     */
     
     
    /**
     *loadclass classes to use elements within form
     */             
    $this->loadClass('textinput', 'htmlelements');
    $this->loadClass('button', 'htmlelements');
    
    
    /**
    *create all languge elements for all form labels
    */
           
    $deptDate  = $this->objLanguage->languageText('phrase_departuredate');
    $deptTime  = $this->objLanguage->languageText('phrase_departuretime');
    $deptCity  = $this->objLanguage->languageText('phrase_departurecity');
    $arrivalDate  = $this->objLanguage->languageText('phrase_arrivaldate');
    $arrivalTime  = $this->objLanguage->languageText('phrase_arrivaltime');
    $arrivalCity  = $this->objLanguage->languageText('phrase_arrivalcity');
    $btnsave  = $this->objLanguage->languageText('word_save');
    $strsave = ucfirst($btnsave);
    $return = $this->objLanguage->languageText('mod_onlineinvoice_returntotravelexpense','onlineinvoice');
    $addanotheritinerary = $this->objLanguage->languageText('mod_onlineinvoice_addanotheritenirary','onlineinvoice');
    $exit  = ucfirst($this->objLanguage->languageText('phrase_exit'));
    $next = ucfirst($this->objLanguage->languageText('phrase_next'));
    $back = ucfirst($this->objLanguage->languageText('word_back'));
    //$btnsave  = $this->objLanguage->languageText('word_save');
    $btnAdd = $this->objLanguage->languageText('mod_onlineinvoice_addanotheritenirary','onlineinvoice');
    $stradd = ucfirst($btnAdd);
    $error_message = $this->objLanguage->languageText('phrase_dateerror');
    $strerror  =  strtoupper($error_message);
    $strexitform  = $this->objLanguage->languageText('mod_onlineinvoice_exitform','onlineinvoice');
    $strsucessfull = $this->objLanguage->languageText('mod_onlineinvoice_valuessubmitted','onlineinvoice');
    $sucessfull = strtoupper($strsucessfull);
/************************************************************************************************************************************************/
   /**
     *create heading -- travel itenirary
     */  
    $this->objIteninary =& $this->newObject('htmlheading','htmlelements');
    $this->objIteninary->type = 3;
    $this->objIteninary->str=$objLanguage->languageText('phrase_traveleritenirary');

/************************************************************************************************************************************************/
   
   /**
    *create all label elements
    */
           
    $lblDeparturedate = $Departuredate;
    $this->objdeparturedate  = $this->newObject('label','htmlelements');
    $this->objdeparturedate->label($deptDate,$lblDeparturedate);

    $lblDeparturetime= $Time;
    $this->objdeparturetime  = $this->newObject('label','htmlelements');
    $this->objdeparturetime->label($deptTime,$lblDeparturetime);

    $lblDeparturecity= $City;
    $this->objdeparturecity  = $this->newObject('label','htmlelements');
    $this->objdeparturecity->label($deptCity,$lblDeparturetime);

    $lblArrivaldate = $Adate;
    $this->objarrivaldate  = $this->newObject('label','htmlelements');
    $this->objarrivaldate->label($arrivalDate,$lblArrivaldate);
  
    $lblArrivaltime= $ATime;
    $this->objarrivaltime  = $this->newObject('label','htmlelements');
    $this->objarrivaltime->label($arrivalTime,$lblArrivaltime);

    $lblArrivalecity  = $ACity;
    $this->objarrivalcity  = $this->newObject('label','htmlelements');
    $this->objarrivalcity->label($arrivalCity,$lblArrivalecity);
/************************************************************************************************************************************************/
  /**
   *  create an instance of the datepicker class
   */ 
    
    /**
     *get the initial date of the start of invoice to display in departure date
     */
     
     //$startdate = $this->getParam('txtbegindate');
     //while (list ($key, $val)  = each ($startdate)) {
       //       if($count == 'begindate') {
         //     $bdate = $val;
           //   }
            //var_dump($bdate);
            //die;  
     //}
/***********************************************************************************************************************************************/              
       
    $this->objdeptdate = $this->newObject('datepicker','htmlelements');
    $name = 'txtdeptddate';
    $date = date('Y-m-d');
    $format = 'YYYY-MM-DD';
    $this->objdeptdate->setName($name);
    $this->objdeptdate->setDefaultDate($date);
    $this->objdeptdate->setDateFormat($format);

    $this->objarrivaldateobj = $this->newObject('datepicker','htmlelements');
    $name = 'txtarraivaldate';
    $date = date('Y-m-d');
    $format = 'YYYY-MM-DD';
    $this->objarrivaldateobj->setName($name);
    $this->objarrivaldateobj->setDefaultDate($date);
    $this->objarrivaldateobj->setDateFormat($format);
    
/************************************************************************************************************************************************/
  /**
   *create all text inputs 
   */     
   
    $this->objtxtdeptcity = $this->newObject('textinput','htmlelements');
    $this->objtxtdeptcity->name   = "txtdeptcity";
    $this->objtxtdeptcity->value  = "";
    $this->objtxtdeptcity->size = 24;
    
    $this->objtxtarrivcity = $this->newObject('textinput','htmlelements');
    $this->objtxtarrivcity->name   = "txtarrivcity";
    $this->objtxtarrivcity->value  = "";
    $this->objtxtarrivcity->size = 24;
/************************************************************************************************************************************************/  
  /**
   *create all form buttons 
   */

    $this->objnext  = new button('next', $next);
    $this->objnext->setToSubmit();
    
    /**
     *validate dates to check that arrive date is not before departure date
     */         
    $onClick = 'var dept_date = document.itinerarymulti.txtdeptddate;
		  			    var arrive_date = document.itinerarymulti.txtarraivaldate;
		  			     
					 
					 
					 
			   	  var acceptance = true;
					      
			      /*value of the arrival date and value of the departure date*/
					    
		  		   var value_end     = arrive_date.value;
					   var value_begin   = dept_date.value;
					   
					 
					 /*checks if dates are right*/
					 
					 if(value_begin > value_end){
					 	acceptance = false;
					 }
					 
							 
					 /*check final condition*/
					 if(!acceptance){
					 	alert(\''.$strerror .'\');
						acceptance = true;
						return false;
					 }else{
          // alert(\''.$sucessfull.'\')
           }';
   $this->objnext->extra = sprintf(' onClick ="javascript: %s"', $onClick );
   
/***********************************************************************************************************************************************/
   $this->objexit  = new button('exit',$exit);
   $this->objexit->setToSubmit();
   $onSelect  = 'alert(\''.$strexitform .'\'); ';
   $this->objexit->extra  = sprintf(' onClick ="javascript: %s"', $onSelect);
     
/************************************************************************************************************************************************/
    
    $this->objAddItinerary  = new button('add', $stradd);
    $this->objAddItinerary->setToSubmit();
    $this->objAddItinerary->extra = sprintf(' onClick ="javascript: %s"', $onClick );
    
    
    $this->objBack  = new button('back', $back);
    $this->objBack->setToSubmit();

/************************************************************************************************************************************************/
  /**
   *create instance of the dropdown list class
   */
   
   $departurename  = 'departuretime';
   $this->objdeparturetimedropdown  = $this->newObject('dropdown','htmlelements');
   $this->objdeparturetimedropdown->dropdown($departurename);
   $this->objdeparturetimedropdown->addOption('00:','00:') ;
   $this->objdeparturetimedropdown->addOption('01:','01:') ;
   $this->objdeparturetimedropdown->addOption('02:','02: ') ;
   $this->objdeparturetimedropdown->addOption('03:','03:') ;
   $this->objdeparturetimedropdown->addOption('04:','04:') ;
   $this->objdeparturetimedropdown->addOption('05:','05:') ;
   $this->objdeparturetimedropdown->addOption('06:','06:') ;
   $this->objdeparturetimedropdown->addOption('07:','07:') ;    
   $this->objdeparturetimedropdown->addOption('08:','08:') ;
   $this->objdeparturetimedropdown->addOption('09:','09:') ;
   $this->objdeparturetimedropdown->addOption('10:','10:') ;
   $this->objdeparturetimedropdown->addOption('11:','11:') ;
   $this->objdeparturetimedropdown->addOption('12:','12:') ;
   $this->objdeparturetimedropdown->addOption('13:','13:') ;
   $this->objdeparturetimedropdown->addOption('14:','14:') ;
   $this->objdeparturetimedropdown->addOption('15:','15:') ;
   $this->objdeparturetimedropdown->addOption('16:','16:') ;
   $this->objdeparturetimedropdown->addOption('17:','17:') ;
   $this->objdeparturetimedropdown->addOption('18:','18:') ;
   $this->objdeparturetimedropdown->addOption('19:','19:') ;
   $this->objdeparturetimedropdown->addOption('20:','20:') ;
   $this->objdeparturetimedropdown->addOption('21:','21:') ;
   $this->objdeparturetimedropdown->addOption('22:','22:') ;
   $this->objdeparturetimedropdown->addOption('23:','23:') ;
   
   $arrivalname  = 'arrivaltime';
   $this->objarrivaltimedropdown  = $this->newObject('dropdown','htmlelements');
   $this->objarrivaltimedropdown->dropdown($arrivalname);
   $this->objarrivaltimedropdown->addOption('00:','00:') ;
   $this->objarrivaltimedropdown->addOption('01:','01:') ;
   $this->objarrivaltimedropdown->addOption('02:','02: ') ;
   $this->objarrivaltimedropdown->addOption('03:','03:') ;
   $this->objarrivaltimedropdown->addOption('04:','04:') ;
   $this->objarrivaltimedropdown->addOption('05:','05:') ;
   $this->objarrivaltimedropdown->addOption('06:','06:') ;
   $this->objarrivaltimedropdown->addOption('07:','07:') ;    
   $this->objarrivaltimedropdown->addOption('08:','08:') ;
   $this->objarrivaltimedropdown->addOption('09:','09:') ;
   $this->objarrivaltimedropdown->addOption('10:','10:') ;
   $this->objarrivaltimedropdown->addOption('11:','11:') ;
   $this->objarrivaltimedropdown->addOption('12:','12:') ;
   $this->objarrivaltimedropdown->addOption('13:','13:') ;
   $this->objarrivaltimedropdown->addOption('14:','14:') ;
   $this->objarrivaltimedropdown->addOption('15:','15:') ;
   $this->objarrivaltimedropdown->addOption('16:','16:') ;
   $this->objarrivaltimedropdown->addOption('17:','17:') ;
   $this->objarrivaltimedropdown->addOption('18:','18:') ;
   $this->objarrivaltimedropdown->addOption('19:','19:') ;
   $this->objarrivaltimedropdown->addOption('20:','20:') ;
   $this->objarrivaltimedropdown->addOption('21:','21:') ;
   $this->objarrivaltimedropdown->addOption('22:','22:') ;
   $this->objarrivaltimedropdown->addOption('23:','23:') ;
   
   
   $minutesname  = 'minutes';
   $this->objminutes  = $this->newObject('dropdown','htmlelements');
   $this->objminutes->dropdown($minutesname);
   $this->objminutes->addOption('00','00') ;
   $this->objminutes->addOption('01','01') ;
   $this->objminutes->addOption('02','02') ;
   $this->objminutes->addOption('03','03') ;
   $this->objminutes->addOption('04','04') ;
   $this->objminutes->addOption('05','05') ;
   $this->objminutes->addOption('06','06') ; 
   $this->objminutes->addOption('07','07') ; 
   $this->objminutes->addOption('08','08') ;
   $this->objminutes->addOption('09','09') ;
   $this->objminutes->addOption('10','10') ;
   $this->objminutes->addOption('11','11') ;
   $this->objminutes->addOption('12','12') ;
   $this->objminutes->addOption('13','13') ;
   $this->objminutes->addOption('14','14') ;
   $this->objminutes->addOption('15','15') ;
   $this->objminutes->addOption('16','16') ;
   $this->objminutes->addOption('17','17') ;
   $this->objminutes->addOption('18','18') ;
   $this->objminutes->addOption('19','19') ;
   $this->objminutes->addOption('20','20') ;
   $this->objminutes->addOption('21','21') ;
   $this->objminutes->addOption('22','22') ;
   $this->objminutes->addOption('23','23') ;
   $this->objminutes->addOption('24','24') ;
   $this->objminutes->addOption('25','25') ;
   $this->objminutes->addOption('26','26') ; 
   $this->objminutes->addOption('27','27') ; 
   $this->objminutes->addOption('28','28') ;
   $this->objminutes->addOption('29','29') ;
   $this->objminutes->addOption('30','30') ;
   $this->objminutes->addOption('31','31') ;
   $this->objminutes->addOption('32','32') ;
   $this->objminutes->addOption('33','33') ;
   $this->objminutes->addOption('34','34') ;
   $this->objminutes->addOption('35','35') ;
   $this->objminutes->addOption('36','36') ;
   $this->objminutes->addOption('37','37') ;
   $this->objminutes->addOption('38','38') ;
   $this->objminutes->addOption('39','39') ;
   $this->objminutes->addOption('40','40') ;
   $this->objminutes->addOption('41','41') ;
   $this->objminutes->addOption('42','42') ;
   $this->objminutes->addOption('43','43') ;
   $this->objminutes->addOption('44','44') ;
   $this->objminutes->addOption('45','45') ;
   $this->objminutes->addOption('46','46') ; 
   $this->objminutes->addOption('47','47') ; 
   $this->objminutes->addOption('48','48') ;
   $this->objminutes->addOption('49','49') ;
   $this->objminutes->addOption('50','50') ;
   $this->objminutes->addOption('51','51') ;
   $this->objminutes->addOption('52','52') ;
   $this->objminutes->addOption('53','53') ;
   $this->objminutes->addOption('54','54') ;
   $this->objminutes->addOption('55','55') ;
   $this->objminutes->addOption('56','56') ; 
   $this->objminutes->addOption('57','57') ; 
   $this->objminutes->addOption('58','58') ;
   $this->objminutes->addOption('59','59') ;
   $this->objminutes->addOption('60','60') ;
     
/************************************************************************************************************************************************/
    
    /**
     *create a link to return to the tev emplate
     */         
    
    //$this->objreturn  =& $this->newobject('link','htmlelements');
    //$this->objreturn->link($this->uri(array('action'=>'createtev'))); /*takes user to the previous template -- tev*/
    //$this->objreturn->link = $return;
    
    /*$this->objmulti  =& $this->newobject('link','htmlelements');
    $this->objmulti->link($this->uri(array('action'=>'addmultiitenirary'))); /*takes user to the next template -- per diem expense*/
    //$this->objmulti->link = $addanotheritinerary;
    
    //$this->objexit  =& $this->newobject('link','htmlelements');
    //$this->objexit->link($this->uri(array('action'=>'NULL')));  /* -- returns banck to original invoice template*/
    //$this->objexit->link = $exit;

    //$this->objnext  =& $this->newobject('link','htmlelements');
    //$this->objnext->link($this->uri(array('action'=>'createexpenses'))); /*takes user to the next template -- per diem expense*/
    //$this->objnext->link = $next;
/************************************************************************************************************************************************/   

    /**
     *create table to place all form elements for the itenirary template 
    */
      
        $myTabIten  = $this->newObject('htmltable','htmlelements');
        $myTabIten->width='100%';
        $myTabIten->border='0';
        $myTabIten->cellspacing = '5';
        $myTabIten->cellpadding ='10';

        $myTabIten->startRow();
        $myTabIten->addCell($this->objdeparturedate->show());
        $myTabIten->addCell($this->objdeptdate->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objdeparturetime->show());
        $myTabIten->addCell($this->objdeparturetimedropdown->show() .  'hr ' . $this->objminutes->show()  . 'min');
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objdeparturecity->show());
        $myTabIten->addCell($this->objtxtdeptcity->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objarrivaldate->show());
        $myTabIten->addCell($this->objarrivaldateobj->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objarrivaltime->show());
        $myTabIten->addCell($this->objarrivaltimedropdown->show() . 'hr ' . $this->objminutes->show()  . 'min');
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objarrivalcity->show());
        $myTabIten->addCell($this->objtxtarrivcity->show());
        $myTabIten->endRow();

        
        $myTabIten->startRow();
        $myTabIten->endRow();

        
        $myTabIten->startRow();
//        $myTabIten->addCell($this->objButtonSubmit->show());
        $myTabIten->addCell('');
        $myTabIten->addCell($this->objAddItinerary->show());
        $myTabIten->endRow();
        
        /*$myTabIten->startRow();
        $myTabIten->addCell($this->objButtonSubmit->show());
        $myTabIten->endRow();*/
        

        /*$myTabIten->startRow();
        $myTabIten->addCell($this->objButtonSave->show());
        $myTabIten->addCell($this->objreturn->show());
        $myTabIten->endRow();*/
 
/************************************************************************************************************************************************/
        
$this->loadClass('tabbedbox', 'htmlelements');
$objmultiitinerary = new tabbedbox();
$objmultiitinerary->addTabLabel('Travelers Itinerary');
$objmultiitinerary->addBoxContent('<br>' . $myTabIten->show() . '<br>' . '<br>' ."<div align=\"center\">" .$this->objBack->show() .' '. $this->objnext->show() . ' ' . $this->objexit->show() ."</div>"  . '<br />' . '<br />');               

        
/************************************************************************************************************************************************/
    /**
     *create the form for a one-way-trip itenirary template
     */         
      
      $this->loadClass('form','htmlelements');
      $objitenirarymultiForm = new form('itinerarymulti',$this->uri(array('action'=>'submitmultiitinerary')));
      $objitenirarymultiForm->displayType = 3;
      $objitenirarymultiForm->addToForm($objmultiitinerary->show());	
      $objitenirarymultiForm->addRule('txtdeptcity', 'Please enter departure city','required');
      $objitenirarymultiForm->addRule('txtarrivcity', 'Please enter arrival city','required'); 
/************************************************************************************************************************************************/
    
            
    /**
    *display the form / output
    */

    echo  "<div align=\"center\">" . $this->objIteninary->show() . "</div>";        
    echo  $objitenirarymultiForm->show();
?>
