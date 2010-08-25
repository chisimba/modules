<html>
  <div id="topdiv">
        <?php

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
  $this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
//$name = $_POST['uname'];
//echo "Welcome ". $name;
$this->loadClass('dropdown','htmlelements');
$this->loadClass('checkbox','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('htmlheading','htmlelements');
//$objcalender = new calendar("calaendar","please select date");
//$objcalender->setDate(1,1,2020);
//echo $objcalender->show();
//$title = new htmlheading("dfgdfgdfgfdgg", 4);
//$title->align = "left";
// $titlelabel = new label("gfgdfgdfg", null);
//     $objElement = new checkbox('m',"Male",TRUE);  // this will checked
//    //$objElement->label ="jdhjdghjdgas";
//    //$objElement->setValue("fhsdklfhdjkfhsdkljf");
//   // $titlelabel = new label ("sdfdsfdsfs","m");
//   // label($labelValue=null, $forId=null)
//               echo $title->show();
//          echo $titlelabel->show();
//     echo $objElement->show();
//
//
//     $dd=&new dropdown('mydropdown');
//   $dd->addOption();
//   $dd->addOption('m','Male');
//   $dd->addOption('f','Female');
//      $dd->addOption('z','Male');
//   $dd->addOption('x','Female');
//      $dd->addOption('c','Male');
//   $dd->addOption('v','Female');
//      $dd->addOption('v','Male');
//   $dd->addOption('b','Female');
//   $dd->setMultiSelected(array("m","f"));
//   $dd->multiple =true;
// echo  $dd->show();
//
// $datePicker = $this->newObject('datepicker', 'htmlelements');
// $datePicker->name = 'storydate';
//  //$datePicker->setName("storydate");
// //$datePicker->setDateFormat("Aug-06-1996");
// // $datePicker->setDefaultDate("2010/02/02");
// //echo $datePicker->show();
 $this->loadClass("textinput","htmlelements");
 $this->loadClass('inputmasks', 'htmlelements');
   $this->loadClass('validator','htmlelements');
      //   echo $objInputMasks->show();
//
// $ti = new textinput("test", "test1","text","10");
//          $ti->setCss('text input_mask mask_date_us');
// echo $ti->show();


        $objTitle = new textinput('title');
                $objTitle->setCss('text input_mask mask_visa');
$objvalidator= $this->getObject('validator','htmlelements');
       // $objvalidator->validateTextOnly($title,'djsfhsdjkfhdshfls');
        //Create a new label for the text labels
      //  $titlelabel = new label ($this->objLanguage->languagetext("mod_helloforms_commenttitle","helloforms"),"title");
        //$objForm->addToForm($titlelabel->show() . "<br />");
       echo $objTitle->show() . "<br />";


                 $objInputMasks = $this->getObject('inputmasks', 'htmlelements');
      //   echo $objInputMasks->show();
    //    $objForm->addToForm($objvalidator->validateTextOnly($title,'djsfhsdjkfhdshfls'));
//$objForm->addToForm($objvalidator->foundErrors());
echo $objInputMasks->show();
            //  $objElement->setBreakSpace("<br>");
                    // $objElement->setBreakSpace("");
      // echo $objElement->show();
//
//                          //      $objElement->addOption('m','Male');
//
//if( $_REQUEST["uname"] )
//{
//
//   $name = $_REQUEST['uname'];
 //  $objElement->addOption('m',$name);
 //  echo $objElement->show();
//}
//echo $element;
 $this->loadClass("textinput","htmlelements");
 $this->loadClass('inputmasks', 'htmlelements');
   $this->loadClass('validator','htmlelements');
      //   echo $objInputMasks->show();
//
// $ti = new textinput("test", "test1","text","10");
//          $ti->setCss('text input_mask mask_date_us');
// echo $ti->show();


//        $objTitle = new textinput('title',$wer);
//
//       echo $objTitle->show() . "<br />";
//    $this->loadClass('hiddeninput','htmlelements');
//     $wer = new hiddeninput('test','value');
//     echo $wer->show();

   $this->loadClass('textarea','htmlelements');
   $ta = new textarea("name","test value",3,100);
   $ta->setAutoGrow(false);
   echo $ta->show()."<br>";

   $this->loadClass('htmlarea','htmlelements');
  // $ha = new htmlarea("name","value",4,50,false);
   $ha = $this->newObject('htmlarea','htmlelements');
   $ha->setName("test anme");
   $ha->setColumns(100);
      $ha->setRows(3);
        $ha->setContent("sdgdgdfgz");
$ha->width ="40.14454%";
$ha->height ="220px";
       // return  $this->HTMLArea->setName($name_of_HTML_area);
$ha->toolbarSet='forms';
      //  return  $this->HTMLArea->setDefaultToolBarSet();
//$ha->setVersion("2.6.3");
//$ha->setFormsToolBar();
echo $ha->show();
   //$ha->htmlarea("name","value",4,50,false);
  // echo $ha->show();
   
?>
    </div>
    </html>
