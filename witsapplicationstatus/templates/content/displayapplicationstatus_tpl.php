<?php

$personnumber = $this->getParam('personnumber');	
//$result=$this->objWSresult->getExample($personnumber);	

$results=$this->objWSresult->getStatus(strtoupper($personnumber));	
//var_dump($results);

$months = array('JAN' => 'January',
               'FEB' => 'February', 
               'MAR' => 'March',
               'APR' => 'April',
               'MAY' => 'May',
               'JUN' => 'June',
               'JUL' => 'July',
               'AUG' => 'August',
               'SEP' => 'September',
               'OCT' => 'October',
               'NEV' => 'November',
               'DEC' => 'December');
               
$table = $this->newObject('htmltable', 'htmlelements');

//sets to use different background colors for alternate table rows
$alternate_row_colors = TRUE;

$table->startHeaderRow();
$table->addHeaderCell('Year',"20","center");
$table->addHeaderCell('Program',"20","center" );
$table->addHeaderCell('Session',"20","center");
$table->addHeaderCell('Program Description / Unit / Field of Study',"50","center");
$table->addHeaderCell('Year of Study',"10",null , "center");
$table->addHeaderCell('Application Status',"50","center");
$table->endHeaderRow();
//print_r($result);
//die();

//if only 1 row returned by web service then it is a single array(not multitple)
//and array elements just referenced by soap message field names
if ($results[0] == null) {
   $name = $results['FirstName']." ".$results['Surname'];	
//    print_r($name);
   $table->startRow();
   $table->addCell($results['AcademicSession']);
   $table->addCell($results['ProgramCode']);
   $table->addCell($months[substr($results['AlternateCode'],5,3)]);
   $table->addCell($results['ProgramDesc']);
   $table->addCell(substr($results['YearOfStudy'],3,1),null,null,"center");
   if ($results['UnitCourseCode']== null)
     $table->addCell($results['ApplicationStatus']);
   $table->endRow();

//if there is a value in this field then add extra row
if ($results['UnitCourseCode']!= null)
   { 
   	 $table->startRow();
   	 $table->addCell("<br>  ");$table->addCell(null);$table->addCell(null);
   	 $table->addCell("   -       ".$results['UnitCode']);
   	 $table->addCell(null);
   	 $table->addCell($results['ApplicationStatus']);
   	 $table->endRow();
   }
}
else { //if multiple rows returned by soap message
 $name = $results[0]['FirstName']." ".$results[0]['Surname'];	
 for ($i = 0; $i < count($results);$i++) {
 	
   //to display alternate rows with different background colors	
   if ($i % 2 == 0) {
    $table->startRow("even-row");}
   else {
    $table->startRow("odd-row");}
   $table->addCell($results[$i]['AcademicSession']);
   $table->addCell($results[$i]['ProgramCode']);
   $table->addCell($months[substr($results[$i]['AlternateCode'],5,3)]);
   $table->addCell($results[$i]['ProgramDesc']);
   $table->addCell(substr($results[$i]['YearOfStudy'],3,1),null,"top","center");
   
   //Display applicationstatus here ONLY if there is a NO second row
   if ($results[$i]['UnitCourseCode']== null)
   $table->addCell($results[$i]['ApplicationStatus']);
   
   //if there is a value in this field then add extra row
   if ($results[$i]['UnitCourseCode']!= null)
   { 
   	 $table->startRow();
   	 $table->addCell("<br>  ");$table->addCell(null);$table->addCell(null);
   	 $table->addCell("   -       ".$results[$i]['UnitCode']);
   	 $table->addCell(null);
   	 $table->addCell($results[$i]['ApplicationStatus']);
   	 $table->endRow();
   }
 }
}
//create button to go back to query form
$objButton1 = new button('submit','Back to Query');
$objButton1->setToSubmit();
$buttons.='<p>'.'<p>'.$objButton1->show();
$actionUrl = $this->uri(array('action' => 'querystatus'));
$objButton1->setOnClick("window.location='$actionUrl'");

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$cssLayout->setLeftColumnContent('<br><br><br><h3>Welcome '.'<br>'.'Wits '.'<br>'.'Applicant</h3>');
$rightSideColumn =  '<h1>Wits Application Status</h1><hr>';
if ($results == null) {
  $rightSideColumn .= 	'<h3> No results found for Application Number: '.$personnumber.' </h3>' ;
} 
else
{
  $rightSideColumn .=  '<h3>Welcome '.$name.' ( '.$personnumber.' )'.' </h3> The status of your application/s to Wits University is as given below :'.'<p>';
  $rightSideColumn .=$content;
  $alternate_row_colors = TRUE;
  $rightSideColumn .=$table->show();
  $rightSideColumn .='<br/><br/><center><h4>Note: Decisions displayed above may be subject to conditions and/or corrections.</h4></center>';
}
$rightSideColumn .='<br>'.'<br>'.'<br>'.$objButton1->show();

$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();

?>