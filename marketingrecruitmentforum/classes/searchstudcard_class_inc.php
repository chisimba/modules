<?php
//class used to read all display search results for student card (SLU)
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates data used to display search results for student card (SLU) 
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version 1.0
* @author Colleen Tinker
*/

class searchstudcard extends object{ 
/**
 * Standard init function
 * @param void
 * @return void
 */    
function init()
{
    	 try {
              $this->loadClass('dropdown', 'htmlelements');
              $this->objLanguage = &$this->getObject('language', 'language');
              $this->loadClass('htmltable','htmlelements');
              $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
			        $this->loadClass('link', 'htmlelements');
    			
    		}catch (Exception $e){
           		echo 'Caught exception: ',  $e->getMessage();
            	exit();
        }
}
/*------------------------------------------------------------------------------*/
/**
 * Method to display all students that completed information cards
 * @param array obj $results, contains all student details
 * Calls function getallstudinfo() from dbstudcard class 
 * loop through contents of $result and create table rows
 * @return obj $myTable      
 */ 
public  function  getAllstudents(){
        
		$details = '';
    $records = '';
    $paging = '';   
		$res = $this->objstudcard->getallstudinfo();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		$results = $this->objstudcard->getstudinfo($startat,$endat);

        $oddEven = 'even';
        $myTable =& $this->newObject('htmltable', 'htmlelements');
        $myTable->cellspacing = '1';
        $myTable->cellpadding = '2';
        $myTable->border='0';
        $myTable->width = '100%';

        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Date'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('School'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Surname'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Name'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Address'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Postal Code'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Telephone Number'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Cell Number'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->addHeaderCell('Email Address'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","", null, "left","header");
        $myTable->endHeaderRow();
     
        $rowcount = 0;
  

      for($i=0; $i< count($results); $i++){
             
         $myTable->startRow();
        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->ENTRYDATE,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->SCHOOLNAME,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->SURNAME,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->POSTADDRESS,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->POSTCODE,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->TELCODE.' '.$results[$i]->TELNUMBER,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->CELLNUMBER,"", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->STUDEMAIL,"", null, "left","$oddOrEven");
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
                 $myTable->width = '100%';
                 $myTable->css_class = 'highlightrows';
                 $myTable->row_attributes = " class = \"$oddEven\"";
                 
                 $rowcount = 0;
                 
                 $myTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell('NO RECORDS FOUND',"100", null, "left","$oddOrEven");
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }  
	$content  =$myTable->show();
  $content = "<center>".$details.$records.$paging."</center> ".$content;
  return $content;
   
}
/*------------------------------------------------------------------------------*/
/**
 * Method to display all students that completed information from a certain school
 * @param array obj $school passed to function, contains all student details from selected school
 * loop through contents of $school and create table rows
 * if condition not met display a no records msg 
 * @return obj $myTable      
 */ 
public  function allstudschool($school){
        //echo '<pre>';
       // print_r($school);
      if(isset($school)){
        
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '150%';
                 $myTable->css_class = 'highlightrows';
                 
                 $myTable->startHeaderRow();
                 $myTable->addHeaderCell('School Name', null,'top','left','header');
                 $myTable->addHeaderCell('Surname', null,'top','left','header');
                 $myTable->addHeaderCell('Name', null,'top','left','header');
                
                 $rowcount = 0;
      
             for($i=0; $i< count($school); $i++){ 
            
                 $myTable->startRow();
                 (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell($school[$i]->SCHOOLNAME,"15%", null, "left","$oddOrEven");
                 $myTable->addCell($school[$i]->SURNAME, "10%", null, "left","$oddOrEven");
                 $myTable->addCell($school[$i]->NAME,"10%", null, "left","$oddOrEven");
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
    
        return $myTable->show();
                 
}      
/*------------------------------------------------------------------------------*/
/**
 * Method to display all students that completed information and qualify for an exemption
 * @param array obj $results passed to function, contains all student details with exemption
 * Call function allstudsexemption() from dbstudcard class 
 * loop through contents of $school and create table rows
 * @return obj $myTable      
 */ 
public  function  allwithexemption(){
    		$details = '';
    $records = '';
    $paging = ''; 
		$res = $this->objstudcard->allstudsexemption($where = 'where exemption = 1');
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->exemption($startat,$endat);
          
    // if($results){   
          $oddEven = 'even';
          $myTable =& $this->newObject('htmltable', 'htmlelements');
          $myTable->cellspacing = '1';
          $myTable->cellpadding = '2';
          $myTable->border='0';
          $myTable->width = '130%';
          $myTable->css_class = 'highlightrows';
   
    
          $myTable->startHeaderRow();
          $myTable->addHeaderCell('School Name', null,'top','left','header');
          $myTable->addHeaderCell('Surname', null,'top','left','header');
          $myTable->addHeaderCell('Name', null,'top','left','header');
          $myTable->addHeaderCell('Qualify for exemption', null,'top','left','header');
          
          $rowcount = 0;
  

     for($i=0; $i< count($results); $i++){
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SCHOOLNAME,"25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->SURNAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell('YES',"25%", null, "left","$oddOrEven");
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

     return $content;
}	
/*------------------------------------------------------------------------------*/
/**
 * Method to display all students that completed information grouped by faculty entered for
 * @param array obj $results passed to function, contains all student details and relevant faculty details
 * Call function allbyfaculty() from dbstudcard class 
 * loop through contents of $results and create table rows
 * @return obj $myTable      
 */
public function studfaculty(){
     		$details = '';
    $records = '';
    $paging = '';    
    //$results = $this->objstudcard->allbyfaculty();
 		$res = $this->objstudcard->allbyfaculty();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->studfacultycount($startat,$endat);

  //if($results){      
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '130%';
         $myTable->css_class = 'highlightrows';
         
         $myTable->startHeaderRow();
         $myTable->addHeaderCell('Surname', null,'top','left','header');
         $myTable->addHeaderCell('Name', null,'top','left','header');
         $myTable->addHeaderCell('Faculty 1st Choice', null,'top','left','header');
          
         $rowcount = 0;
  
   
        for($i=0; $i< count($results); $i++){
              //test if faculty values are null 
              if(!empty($results[$i]->FACULTY)){
                  $fac = $results[$i]->FACULTY;
              } else {
                 $fac = "";
              }
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SURNAME, "15%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "15%", null, "left","$oddOrEven");
         $myTable->addCell($fac,"15%", null, "left","$oddOrEven");
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

     return $content;
         
}
/*------------------------------------------------------------------------------*/
public function studfaculty2ndchoice(){
    $details = '';
    $records = '';
    $paging = '';     
  //$results = $this->objstudcard->allbyfaculty();
   	$res = $this->objstudcard->allbyfaculty();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->studfacultycount2($startat,$endat);

  //if($results){      
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '130%';
         $myTable->css_class = 'highlightrows';
         
         $myTable->startHeaderRow();
         $myTable->addHeaderCell('Surname', null,'top','left','header');
         $myTable->addHeaderCell('Name', null,'top','left','header');
         $myTable->addHeaderCell('Faculty 2nd Choice', null,'top','left','header');
          
         $rowcount = 0;
  
   
        for($i=0; $i< count($results); $i++){
              //test if faculty values are null 
              if(!empty($results[$i]->FACULTY2)){
                  $fac = $results[$i]->FACULTY2;
              } else {
                 $fac = "";
              }
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SURNAME, "35%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "35%", null, "left","$oddOrEven");
         $myTable->addCell($fac,"35%", null, "left","$oddOrEven");
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

     return $content;
         
}
/**
 * Method to display all students that completed information by course 
 * @param array obj $results passed to function, contains all student details and relevant course details
 * Call function allbycourse() from dbstudcard class 
 * loop through contents of $results and create table rows
 * @return obj $myTable      
 */
public function studcourse(){
    $details = '';
    $records = '';
    $paging = '';  
    //$results = $this->objstudcard->allbycourse();
    $res = $this->objstudcard->allbycourse();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->studcoursecount($startat,$endat);

    // if($results){     
          $oddEven = 'even';
          $myTable =& $this->newObject('htmltable', 'htmlelements');
          $myTable->cellspacing = '1';
          $myTable->cellpadding = '2';
          $myTable->border='0';
          $myTable->width = '130%';
          $myTable->css_class = 'highlightrows';
         
          $myTable->startHeaderRow();
          $myTable->addHeaderCell('Surname', null,'top','left','header');
          $myTable->addHeaderCell('Name', null,'top','left','header');
          $myTable->addHeaderCell('Course 1st Choice', null,'top','left','header');
          $myTable->addHeaderCell('Faculty 1st Choice', null,'top','left','header');
         
          $rowcount = 0;
  

        for($i=0; $i< count($results); $i++){
             //test if faculty values empty        
             if(!empty($results[$i]->FACULTY)){
                  $fac = $results[$i]->FACULTY;
              } else {
                 $fac = "";
              }
              
              //test if course values are null
              if(!empty($results[$i]->COURSE)){
                  $crse = $results[$i]->COURSE;
              } else {
                 $crse = "";
              }
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SURNAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($crse,"25%", null, "left","$oddOrEven");
         $myTable->addCell($fac,"25%", null, "left","$oddOrEven");
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

     return $content;
         
}    
/*------------------------------------------------------------------------------*/
public function studcourse2ndchoice(){
    		$details = '';
    $records = '';
    $paging = '';   
    //      $results = $this->objstudcard->allbycourse();
    $res = $this->objstudcard->allbycourse();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->studcoursecount2($startat,$endat);

    // if($results){     
          $oddEven = 'even';
          $myTable =& $this->newObject('htmltable', 'htmlelements');
          $myTable->cellspacing = '1';
          $myTable->cellpadding = '2';
          $myTable->border='0';
          $myTable->width = '130%';
          $myTable->css_class = 'highlightrows';
         
          $myTable->startHeaderRow();
          $myTable->addHeaderCell('Surname', null,'top','left','header');
          $myTable->addHeaderCell('Name', null,'top','left','header');
          $myTable->addHeaderCell('Course 2nd Choice', null,'top','left','header');
          $myTable->addHeaderCell('Faculty 2nd Choice', null,'top','left','header');
         
          $rowcount = 0;
  

        for($i=0; $i< count($results); $i++){
             //test if faculty values empty        
             if(!empty($results[$i]->FACULTY2)){
                  $fac = $results[$i]->FACULTY2;
              } else {
                 $fac = "";
              }
              
              //test if course values are null
              if(!empty($results[$i]->COURSE2)){
                  $crse = $results[$i]->COURSE2;
              } else {
                 $crse = "";
              }
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SURNAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($crse,"25%", null, "left","$oddOrEven");
         $myTable->addCell($fac,"25%", null, "left","$oddOrEven");
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

     return $content;
         
} 
/**
 * Method to display all students that completed information by area of SCHOOL
 * @param array obj $results passed to function, contains all student details and relevant area details
 * Call function getstudbyarea() from dbstudcard class 
 * loop through contents of $results and create table rows
 * @return obj $myTable      
 */
public function studarea(){
    		$details = '';
    $records = '';
    $paging = '';    
         // $results = $this->objstudcard->getstudbyarea();
    $res = $this->objstudcard->getstudbyarea();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->studarealimit($startat,$endat);

    // if($results){  
          $oddEven = 'even';
          $myTable =& $this->newObject('htmltable', 'htmlelements');
          $myTable->cellspacing = '1';
          $myTable->cellpadding = '2';
          $myTable->border='0';
          $myTable->width = '130%';
          $myTable->css_class = 'highlightrows';
         
          $myTable->startHeaderRow();
          $myTable->addHeaderCell('Surname', null,'top','left','header');
          $myTable->addHeaderCell('Name', null,'top','left','header');
          $myTable->addHeaderCell('Postal Address', null,'top','left','header');
         // $myTable->addHeaderCell('School Name', null,'top','left','header');
          $myTable->addHeaderCell('Area', null,'top','left','header');
        
        
          $rowcount = 0;
  
/*    foreach($results as $sessCard){*/
      for($i=0; $i< count($results); $i++){
     
           $myTable->startRow();
           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
           $myTable->addCell($results[$i]->SURNAME, "25%", null, "left","$oddOrEven");
           $myTable->addCell($results[$i]->NAME,"25%", null, "left","$oddOrEven");
           $myTable->addCell($results[$i]->POSTADDRESS,"25%", null, "left","$oddOrEven");
           //$myTable->addCell($results[$i]->SCHOOLNAME,"15%", null, "left","$oddOrEven");
           $myTable->addCell($results[$i]->AREA,"25%", null, "left","$oddOrEven");
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

     return $content;
}     
/*------------------------------------------------------------------------------*/
/**
 * Method to display all students that completed information and are considered sdcases(i.e -- all students that don't have an exemption)
 * @param array obj $results passed to function, contains all student details and relevant sdcase info
 * Call function sdcases() from dbstudcard class 
 * loop through contents of $results and create table rows
 * @return obj $myTable      
 */
public function studsdcase(){
    $details = '';
    $records = '';
    $paging = '';    
    //$results = $this->objstudcard->sdcases($where = 'where sdcase = 1 and exemption = 0');
    $res = $this->objstudcard->sdcases($where = 'where sdcase = 1 and exemption = 0');
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 25);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
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
          $prevLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschool','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschool', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschool','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		 $results = $this->objstudcard->studsdcasecount($startat,$endat);

   // if($results){
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '130%';
         $myTable->css_class = 'highlightrows';
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell('Surname', null,'top','left','header');
         $myTable->addHeaderCell('Name', null,'top','left','header');
         $myTable->addHeaderCell('School Name', null,'top','left','header');
         $myTable->addHeaderCell('SD CASE', null,'top','left','header');
        
         $rowcount = 0;

      for($i=0; $i< count($results); $i++){
       
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SURNAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "25%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->SCHOOLNAME,"25%", null, "left","$oddOrEven");
         $myTable->addCell('YES',"25%", null, "left","$oddOrEven"); 
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

     return $content;
}     
/*------------------------------------------------------------------------------*/
/**
 * Method to display all students that completed information by selected faculty
 * @param array obj $faculty passed to function, contains all student details for the selected faculty
 * loop through contents of $results and create table rows
 * @return obj $myTable      
 */
public function countstudfaculty($faculty11){
  
     
     if(isset($faculty11)){

             $oddEven = 'even';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '100%';
             $myTable->css_class = 'highlightrows';
      
             $myTable->startHeaderRow();
             $myTable->addHeaderCell('Surname', null,'top','left','header');
             $myTable->addHeaderCell('Name', null,'top','left','header');
             $myTable->addHeaderCell('School Name', null,'top','left','header');
             $myTable->addHeaderCell('Faculty Name', null,'top','left','header');
            // $myTable->addHeaderCell('Total Students', null,'top','left','header');
            
            
             $rowcount = 0;
  
       // foreach($faculty as $sessSD){
       for($i=0; $i < count($faculty11); $i++){ 
             
               
               
              $myTable->startRow();
              (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
              $myTable->addCell($faculty11[$i]->SURNAME, "15%", null, "left","$oddOrEven");
              $myTable->addCell($faculty11[$i]->NAME,"15%", null, "left","$oddOrEven");
              $myTable->addCell($faculty11[$i]->SCHOOLNAME,"15%", null, "left","$oddOrEven");
              $myTable->addCell($faculty11[$i]->FACULTY,"15%", null, "left","$oddOrEven");
             // $myTable->addCell($faculty11[$i]->TOTSTUD,"15%", null, "left","$oddOrEven"); 
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
                 $myTable->width = '100%';
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
          
           return $myTable->show();
           
  //  }
}     
/*------------------------------------------------------------------------------*/
/**
 * Method to display student detail, if already existing within db
 * @param array obj $idsearch passed to function, contains all student details with a unique id no
 * loop through contents of $idsearch and create table rows
 * if condition true and session is empty display a no records msg and a link that will allow user to capture student details for that id number 
 * @return obj $myTable      
 */
public  function searchID($idsearch){
   
  $this->loadClass('link', 'htmlelements');      
  $StudentCardLink = new link($this->uri(array('action' => 'capturestudcard', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Capture Student Card')));
  $StudentCardLink->link = 'Capture Student Card';
  
  $StudentLink = new link($this->uri(array('action' => 'editoutput', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Edit Student Details')));
  $StudentLink->link = 'Edit Student Details';
  
  $objLanguage =& $this->getObject('language', 'language');
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=3;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_norecords','marketingrecruitmentforum');
  
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type=3;
  $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_existingrec11','marketingrecruitmentforum');
           
          if(!empty($idsearch)){
                
                           $myTable =& $this->newObject('htmltable', 'htmlelements');
                           $myTable->cellspacing = '1';
                           $myTable->cellpadding = '2';
                           $myTable->border='0';
                           $myTable->width = '70%';
                           $myTable->css_class = 'highlightrows';
                           ///$myTable->row_attributes = " class = \"$oddEven\"";
                    
                     
                    // foreach($idsearch as $sessCard){
                    for($i=0; $i< count($idsearch); $i++){
                          $rowcount = 0; 
                         /**
                          * get the value for student exemption
                          * exemption value is stored as boolean in db i.e 1 or 0                  
                          * therefore if $exemp is true string $val = YES ...more understandable to user 
                          */                                                     
                      //    $exemp = $idsearch[$i]->EXEMPTION;
                      //    $sdval22  = $idsearch[$i]->SDCASE;
                          $entrydateval = $idsearch[$i]->ENTRYDATE;
                          //$idnum  = $idsearch[$i]->IDNUMBER;
                          //$name = $idsearch[$i]->NAME;
                          //$surname  = $idsearch[$i]->SURNAME;
                          $dateEntered  = date("d-M-Y", strtotime($entrydateval));
                          $dob  = date("d-M-Y", strtotime($idsearch[$i]->DOB));
                          
                          if($idsearch[$i]->EXEMPTION == 1){
                                $exemptionval = 'YES';
                          }elseif($idsearch[$i]->EXEMPTION == 0){
                                $exemptionval = 'NO';
                          }else{
                                $exemptionval = 'Null';
                          }
                
                          if($idsearch[$i]->RESIDENCE == 1){
                                $residence = 'YES';
                          }else{
                                $residence = 'NO';
                          }
                        
                          if($idsearch[$i]->SDCASE == 1){
                              $sdvalues = 'YES';
                          }elseif($idsearch[$i]->SDCASE == 0){
                              $sdvalues = 'NO';
                          }else{
                              $sdvalues = 'Null';
                          }  
                          
                           $idno  = $this->getSession('idno');
                           $firstname = $this->getSession('firstname');
                           $lastname = $this->getSession('lastname');
                           $heading = $this->objheading->show();
                           $h = strtoupper($heading);
                           $myTable->startHeaderRow();
                           $myTable->addHeaderCell($h, null,'top','left','header');
                           $myTable->addHeaderCell('<h3>'.$idsearch[$i]->SURNAME.' '.$idsearch[$i]->NAME.'<h3>', null,'top','left','header');
                           $myTable->endHeaderRow();
                            
                           //$myTable->startRow();
                           //$myTable->addCell('<b>'.$idsearch[$i]->IDNUMBER.'<b/>');
                           //$myTable->endRow();
                            
                           /*$myTable->startRow();
                           $myTable->addCell("");
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           $myTable->addCell("");
                           $myTable->endRow();*/
                     
                      
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("ID Number","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->IDNUMBER,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                        
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Campaign Date","15%", null, "left",$oddOrEven);
                           $myTable->addCell(strtoupper($dateEntered),"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                      
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Surname","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->SURNAME,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                        
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Name","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->NAME,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;                           
                           $myTable->endRow(); 
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Date of birth","15%", null, "left",$oddOrEven);
                           $myTable->addCell($dob,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;                           
                           $myTable->endRow(); 
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Grade","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->GRADE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;                           
                           $myTable->endRow(); 
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("School Name","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->SCHOOLNAME,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Postal Address","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->POSTADDRESS,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Postal Code","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->POSTCODE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Residential Area","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->AREA,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Telephone Number","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->TELNUMBER,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Telephone Code","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->TELCODE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell('Cellphone Number',"15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->CELLNUMBER,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
        
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell('Email Address',"15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->STUDEMAIL,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell('Subjects and results for grade',"15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->MARKGRADE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();  
                           
                             $myTable->startRow();
                             (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 1',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT1 .' '.$idsearch[$i]->GRADETYPE1.' '.$idsearch[$i]->MARK1 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 2',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT2.' '.$idsearch[$i]->GRADETYPE2.' '.$idsearch[$i]->MARK2 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 3',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT3.' '.$idsearch[$i]->GRADETYPE3.' '.$idsearch[$i]->MARK3 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 4',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT4.' '.$idsearch[$i]->GRADETYPE4.' '.$idsearch[$i]->MARK4 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 5',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT5.' '.$idsearch[$i]->GRADETYPE5.' '.$idsearch[$i]->MARK5 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 6',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT6.' '.$idsearch[$i]->GRADETYPE6.' '.$idsearch[$i]->MARK6 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Subject 7',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SUBJECT7.' '.$idsearch[$i]->GRADETYPE7.' '.$idsearch[$i]->MARK7,"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $myTable->endRow();
                            
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell("Participated in sports","15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SPORTPART,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                          
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell("Leadership Position(s)","15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->LEADERSHIPPOS,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell("Sport code(s)","15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SPORTCODE,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell("Apply for sport bursary","15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->SPORTBURSARY,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell("Faculty 1st choice","15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->FACULTY,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Faculty 2nd choice',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->FACULTY2,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Course 1st choice',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->COURSE,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Course 2nd choice',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->COURSE2,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Department information',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($idsearch[$i]->INFODEPARTMENT,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
        
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Residence',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($residence,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Exemption',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($exemptionval,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                            
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell('Senate Discretionary',"15%", null, "left",$oddOrEven);
                            $myTable->addCell($sdvalues,"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                             
                            $myTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addCell($StudentLink->show(),"15%", null, "left",$oddOrEven);
                            $myTable->addCell('',"15%", null, "left",$oddOrEven);
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endRow();
                  }
          
      }else{

                   $myTable =& $this->newObject('htmltable', 'htmlelements');
                   $myTable->cellspacing = '1';
                   $myTable->cellpadding = '2';
                   $myTable->border='0';
                   $myTable->width = '30%';
                   
                   $idno  = $this->getSession('idno');
                   $firstname = $this->getSession('name');
                   $name = ucfirst($firstname); 
                   $lastname = $this->getSession('surname');
                   $surname = ucfirst($lastname);
                   
                   $myTable->startRow();
                   $myTable->addCell('<b>'.'<h3>'.'No Records found for'.' '.$surname.' '.$name.'<b/>'.'<h3/>');
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->addCell($StudentCardLink->show());
                   $myTable->endRow();
    }    

                   return $myTable->show();     
}      
/*-----------------------------------------------------------------------------------------------------*/            
}//end of class  
?>
