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
//template to display report showing all students that qualify for entry
    /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('htmlheading','htmlelements');
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('textinput','htmlelements');
        $this->loadClass('link','htmlelements');
       
       $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
       
       /**
        *form heading
        */                
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str='Incomplete Student Entries';
        
        $this->objheading =& $this->newObject('htmlheading','htmlelements');
        $this->objheading->type=3;
        $this->objheading->str='All Student entries where exemption = NO and sdcase = NO';
        
//    public  function  allwithexemption(){
		$details = '';
    $records = '';
    $paging = ''; 
		$res = $this->objstudcard->getincompletedata($where = 'where SDCASE = 0 and EXEMPTION = 0');
	//	var_dump($res);die;
		//VAR_DUMP($res);
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

if ($stdCount > 0){
      //***start of pages***
     $links_code = "";
     $pageCount = $stdCount/$dispCount;

     $showlinks =& $this->getObject('htmlHeading','htmlelements');
     if ($pageCount != floor($pageCount)) {
         $pageCount = strtok(($pageCount+1), ".");
     }
     $startat = ($page - 1) * $dispCount;
     $endat = $startat + $dispCount;
     if ($endat > $stdCount){
        $endat = $stdCount;
     }
     $dispcountfield = new textinput("dispcount", $dispCount,  "hidden", NULL);
     $goButton = & $this->newObject('button','htmlelements');
   	 $cancelButton = & $this->newObject('button','htmlelements');
     $goButton = new button("submit",'Go');
     $goButton->setToSubmit();
	   $cancelButton = new button("cancel", 'Cancel');
     $cancelButton->setToSubmit();
     $dropdown =& $this->newObject("dropdown", "htmlelements");
	   $dropdown->name = 'page';
    	for ($i = 1; $i <= $pageCount; $i++){
    	    $dropdown->addOption($i,$i."&nbsp;&nbsp;&nbsp;");
            if ($i == $page){
                $dropdown->setSelected($i);
            }
    	}
      if ($page > 1){
          $prevLink = new link();
          $prevLink->href=$this->uri(array('action'=>'facultyuse','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Previous';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Previous';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'facultyuse','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'facultyuse', 'page'=>($page-1), 'dispcount'=>$dispCount)));
	$objPaging->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.$pageCount.'</b>'.' '.$goButton->show()."&nbsp;&nbsp;&nbsp;&nbsp;"."<span class='menulink'><b>".$prev."&nbsp;&nbsp;".$next.'</b></span></center>');
    $paging = $objPaging->show();

    $records = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $stdCount);


				
		$start=$startat+1;
     $viewpages = new link();
	
     for ($n=0; $n < $pageCount; $n++) {
         $stdCountR = ($n * $dispCount);
         $num = $n + 1;
         if ($num != $page){
            $viewpages->href=$this->uri(array('action'=>'facultyuse','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
            $viewpages->link = "$num";
            $viewpages->style = "text-decoration:none";
            $links_code .= $viewpages->show();
         }else{
            $links_code .= "$num";
         }
         if ($num == $pageCount){
              // $links_code .= " ";
         }else if($n < $pageCount){
            $links_code .= " | ";
         }
     }
     $endl = $startat + $dispCount;

     if($stdCount<$dispCount){
	     $endl=$stdCount;
     }
	 if ($endl > $stdCount){
        $endl = $stdCount;
     }
     
     $viewp ="";
     $viewn ="";

     if ($startat > 1){
        $page = $this->getParam('pg');
        $pg = $page - 1;
        $stdCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'facultyuse','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Previous';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'facultyuse','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewnext->link = 'Next';
        $viewnext->style = "text-decoration:none";
        $viewn = "| ".$viewnext->show();
     }

     $Rectbl =" ";
     $Rectbl.="Results <b>$start - $endl</b> of <b>$stdCount</b>";
     $records = $Rectbl;
     $showlinks->str = "$viewp $links_code $viewn";
     $showlinks->align="center";

      if($stdCount < $dispCount){
          $pagelinks = "";
      }else{
          $pagelinks = $showlinks->show();
      }
             //***end of pagination***
		 $results = $this->objstudcard->incompletestudlimit($startat,$endat);
          
    // if($results){   
          $oddEven = 'even';
          $myTable =& $this->newObject('htmltable', 'htmlelements');
          $myTable->cellspacing = '1';
          $myTable->cellpadding = '2';
          $myTable->border='0';
          $myTable->width = '100%';
          $myTable->css_class = 'highlightrows';
   
    
          $myTable->startHeaderRow();
          $myTable->addHeaderCell('School Name', null,'top','left','header');
          $myTable->addHeaderCell('ID Number', null,'top','left','header');
          $myTable->addHeaderCell('Surname', null,'top','left','header');
          $myTable->addHeaderCell('Name', null,'top','left','header');
          $myTable->addHeaderCell('Exemption', null,'top','left','header');
          $myTable->addHeaderCell('SDCASE', null,'top','left','header');
          
          $rowcount = 0;
  

     for($i=0; $i< count($results); $i++){
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SCHOOLNAME,"25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->IDNUMBER,"15%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->SURNAME, "15%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "15%", null, "left","$oddOrEven");
         $myTable->addCell('NO',"10%", null, "left","$oddOrEven");
         $myTable->addCell('NO',"10%", null, "left","$oddOrEven");
         $myTable->row_attributes = " class = \"$oddOrEven\"";
         $rowcount++;
         $myTable->endRow();
        
    }  
    }else{
    
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '130%';
                 $myTable->css_class = 'highlightrows';
                 $myTable->row_attributes = " class = \"$oddEven\"";
                 
                 $rowcount = 0;
                 
                 $myTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell('NO RECORDS FOUND',"130%", null, "left","$oddOrEven");
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
        
    }
    	$content  =$myTable->show();
      $content = "<center>".$details.$records.$paging."</center> ".$content;

     //return $content;
//}	  
      /**
       *create print button
       */
      $this->objPrint  = $this->newObject('button','htmlelements');
      $this->objPrint->name = 'Print_Report';
      $this->objPrint->setValue('Print Report'); 
      $this->objPrint->setOnclick('printFriendly();');

      $objForm = new form('reportfacultydata',$this->uri(array('action'=>'facultyuse')));
      $objForm->displayType = 3;
      $objForm->addToForm($this->objMainheading->show() . '<br />'."<span class=error>".$this->objheading->show()."</span>" .'<br />'.$content);
       
      $frmElements = $objForm->show().'<br />';   
      $printView='<div id="printFocus">'.$frmElements.'</div>';
      echo $printView.'<br />'.'<br />';
      echo  $this->objPrint->show(); 
?>
