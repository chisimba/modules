<?php

$this->loadClass('form','htmlelements');
//$this->loadClass('dropdown','htmlelements');
//$this->loadClass('radio','htmlelements');
$this->loadClass('textinput','htmlelements');
//$this->loadClass('textarea','htmlelements');
$this->loadClass('htmlheading','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('fieldset','htmlelements');
$this->loadClass('fieldsetex','htmlelements');

//echo '<p><p><p>';
//$title = "<h2> Application Status Query </h2> <p>";
//echo '<p><p><p>';

//Create fieldset for Application Number
$fieldsetAppNo = new fieldsetex('','','','','');
$fieldsetAppNo->setLegend(" Enter Your Application Number ");

//create input fields
$lblpersonnumber = new label("Application Number");
$personnumber =  new textinput('personnumber', '');

//Add input field to fieldset
$fieldsetAppNo->addLabelledField($lblpersonnumber,$personnumber);

$break1= '<br>'.'<br>';
$break2= '<br>'.'<br>'.'<br>'.'<br>';

//buttons
$objButton1 = new button('submit','Get Application Status');
$objButton1->setToSubmit();
$buttons.='<p>'.'<p>'.$objButton1->show();
//$objButton2 = new button('cancel','Cancel');
//$buttons.='&nbsp;&nbsp;&nbsp'.$objButton2->show();

//Create fieldset for buttons
$objFieldsetButttons = new fieldset();
$objFieldsetButttons->contents = $buttons;

/************** Build form **********************/
//when form is submitted this action is passed to controller
$objForm = new form('FormName',$this->uri(array('action'=>'getstatus')));
//$objForm = new form('FormName',$this->uri(array('action'=>'getexample')));
$objForm->addToForm($break2);
//$objForm->addToForm($title);
//$objForm->addToForm($break2);
$objForm->addToForm($fieldsetAppNo);
//$objForm->addToForm($break1);
$actionUrl = $this->uri(array('action' => ''));
$objButton1->setOnClick("window.location='$actionUrl'");
$objForm->addToForm($break1);
$objForm->addToForm($objFieldsetButttons);

$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$cssLayout->setLeftColumnContent('<br><br><br><h3>Welcome '.'<br>'.'Wits '.'<br>'.'Applicant</h3>');
$rightSideColumn =  '<h1>Wits Application Query</h1><hr>';
//$rightSideColumn .=  '<h3>Welcome '.$results[0]['FirstName'].' '.$results[0]['Surname'].', </h3> The status of your application/s to Wits University is as given below :'.'<p>';
$rightSideColumn .=$content;
$rightSideColumn .=$objForm->show();

//$rightSideColumn .='<br/><br/><center><h4>Note: Decisions displayed above may be subject to conditions and/or corrections.</h4></center>';
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();

//echo $objForm->show();

?>