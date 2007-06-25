
<?php
/*
echo '<applet id="presentationsapplet" width="800" height="600" code="com.sun.star.lib.loader.Loader.class">';
echo '    <param name="archive" value="'.$this->presentationsURL.'/presentations.jar,'.$this->presentationsURL.'/officebean.jar"/> ';
echo "</applet> ";
*/

// set up html elements
$objHead=$this->newObject('htmlheading','htmlelements');

$objHead2 = $this->newObject('htmlheading','htmlelements');

$objHead3 = $this->newObject('htmlheading','htmlelements');
/**************** set up display page ********************/
$str1= 'Wellcome to the realtime presentations module';
$str2= 'Using this module, you can view Open Office 2.0 or later Presentations';
$str3='Click on <a href="'.$this->presentationsURL.'/Presentations.jnlp">Open Office Viewer 0.1</a> to launch'; 

$objHead->type=3;
$objHead->str=$str1;

$aobjHead2->type=3;
$objHead2->str=$str2;


$aobjHead3->type=3;
$objHead3->str=$str3;



echo $objHead->show();
echo $objHead2->show();
echo $objHead3->show();




?>
