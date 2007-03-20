<script language="javascript">

			var gAutoprint = true; // Flag for whether or not to automatically call the print function

			function printFriendly()
			{
			if (document.getElementById!= null)
			{
			var html = '<HTML>\n<HEAD>\n';

			if (document.getElementsByTagName!= null)
			{
			var headTags = document.getElementsByTagName("head");
			if (headTags.length > 0)
			html += headTags[0].innerHTML;
			}

			html += '\n</HE' + 'AD>\n<BODY>\n';

			var printPageElem = document.getElementById("printFocus");

			if (printPageElem!= null)
			{
			html += printPageElem.innerHTML;
			}
			else
			{
			alert("Could not find the printReady section in the HTML");
			return;
			}

			html += '\n</BO' + 'DY>\n</HT' + 'ML>';

			var printWin = window.open("","printFocus");
			printWin.document.open();
			printWin.document.write(html);
			printWin.document.close();
			if (gAutoprint)
			printWin.print();
			}
			else
			{
			alert("Sorry, the printer friendly feature works\nonly in javascript enabled browsers.");
			}
			}
</script>
<?php
//create a template to display a report for all students in a faculty

     /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('htmlheading','htmlelements');
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('dropdown','htmlelements'); 
       $this->loadClass('button','htmlelements'); 
       
       $this->dbstudentcard  = & $this->getObject('dbstudentcard','marketingrecruitmentforum'); 
       $res = $this->dbstudentcard->faccountval($facultyname);
       
       
      
      for($i=0; $i< count($res); $i++){
          $count='';
          $count  = $count + $res[$i]->TOTSTUD;
       
      }
      $val  = $count;
/*------------------------------------------------------------------------------*/       
       /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_reportfaculty1','marketingrecruitmentforum');
        
        $this->objnamehead =& $this->newObject('htmlheading','htmlelements');
        $this->objnamehead->type=3;
        $this->objnamehead->str=$objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum') .':' . ' '.$facultyname;//
        
        $this->objtotstud =& $this->newObject('htmlheading','htmlelements');
        $this->objtotstud->type=3;
        $this->objtotstud->str=$objLanguage->languageText('mod_marketingrecruitmentforum_entrytotal1','marketingrecruitmentforum')  . ':'. ' ' .$val;
        
/*------------------------------------------------------------------------------*/
      /**
       *create dropdownlist with faculty values
       */
      $facultyselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum');
      $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
      $faculty = $this->objfaculties->getFaculties();

      //store faculty values into an array
        for($i=0; $i < count($faculty); $i++){
            $facVAL[$i]=$faculty[$i]->NAME;
        }
      
      $facList = new dropdown('facnames');  
      sort($facVAL);   
      foreach($facVAL as $sessf){
          $facList->addOption(NULL, ''.$facultyselect); 
          $facList->addOption($sessf,$sessf); 
      }
     
      $facList->setSelected($this->getParam('facnames'));
      $facList->extra = 'onChange="document.reportfaculty.submit();"';
      
     //dropdown heading
     $this->objheading =& $this->newObject('htmlheading','htmlelements');
     $this->objheading->type=3;
     $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum') .' '. $facList->show();
        
/*------------------------------------------------------------------------------*/
      $this->objstudcard  = & $this->newObject('searchstudcard','marketingrecruitmentforum');
      $facultydetails = $this->objstudcard->countstudfaculty($faculty11);
      
/*------------------------------------------------------------------------------*/
     	$this->objPrint  = $this->newObject('button','htmlelements');
      $this->objPrint->name = 'Print_Report';
      $this->objPrint->setValue('Print Report'); 
      $this->objPrint->setOnclick('printFriendly();');
      
    /**
     *create a form to place all elements on
     */
      $objForm = new form('reportfaculty',$this->uri(array('action'=>'reportdropdown')));
      $objForm->displayType = 3;
      $objForm->addToForm($this->objMainheading->show().'<br />' .'<br />'.$this->objheading->show().'<br />'.$this->objnamehead->show().'<br />'.$this->objtotstud->show().'<br />' .'<br />' .$facultydetails) . '</br>' .'<br />';
/*-------------------------------------------------------------------------------*/        
      /**
       *display contents to screen
       */
       
       $frmElements = $objForm->show().'<br />';   
       $printView='<div id="printFocus">'.$frmElements.'</div>';
       echo $printView;
       echo  $this->objPrint->show(); 			
/*------------------------------------------------------------------------------*/            
?>
