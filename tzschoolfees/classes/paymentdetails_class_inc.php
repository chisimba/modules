<?php
/* 
 * @author john richard
 * @module name:fee module
 * @date 2011 05 06
 * and open the template in the editor.
*/
//class which shows student payment details
class paymentdetails extends Object {
    public $lang;

    function  init() {

        $this->lang=$this->getObject('language','language');

    }
    //function to load clases
    function loadElements() {
        $this->loadClass('Validator', 'htmlelements');
        $this->loadClass('htmlTable','htmlelements');
        $this->loadClass('form','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('label','htmlelements');
        $this->loadClass('textarea','htmlelements');
        $this->loadClass('button','htmlelements');
        $this->loadClass('datepicker','htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('fieldset','htmlelements');
        
    }
//function for building a form
    function buildForm() {
//call function loadElemnts
        $this->loadElements();

//creating new objects

        $objform = new form('payment',  $this->getAction());
        $objform->method='post';
        

        $formlabel = new label('<h2>STUDENT PAYMENT DETAILS</h2><br>');

        $objform->addToForm($formlabel->show());

        $validate = $this->getObject('Validator', 'htmlelements');
//student full name

        $fname=new label('First name');
        $fnamefield = new textinput('fname');
     
        
         $lname=new label('Surname');
         $lnamefield = new textinput('lname');

         $mname=new label('Other');
         $mnamefield = new textinput('mname');


//student class
        $classlabel=new label('Class name');
        $classfield = new dropdown('class');
        $classfield->addOption('f1','FORM ONE');
        $classfield->addOption('f2','FORM TWO');
        $classfield->addOption('f3','FORM THREE');
        $classfield->addOption('f4','FORM FOUR');
        $classfield->addOption('f5','FORM FIVE');
        $classfield->addOption('f6','FORM SIX');

//student stream
        $streamlabel=new label('Stream: ');
        $streamfield = new dropdown('stream');
        $streamfield->addOption('a','A');
        $streamfield->addOption('b','B');
        $streamfield->addOption('c','C');
        $streamfield->addOption('d','D');
        $streamfield->addOption('e','E');
        $streamfield->addOption('f','F');

//Bank statement
        $banklabel = new label('Bank name: ');
        $bankfield = new dropdown('bank');
        $bankfield->addOption('crdb','CRDB');
        $bankfield->addOption('nmb','NMB');
        $bankfield->addOption('nbc', 'NBC');
        $bankfield->addOption('exim','EXIM BANK');

        $branchlabel = new label('Branch');
        $branchfield = new textinput('branch');



//amount paid by student
        $amountlabel=new label('Amount ');
        $amountfield = new textinput('amount');

 //Date when payment were done
        $datelabel = new label('Date ');
        $datepiki = $this->getObject('datepicker', 'htmlelements');
        $datepiki->setName('payment_date');

//installments by student
        $installmntlabel=new label('Installment');


        $installmentfield = new dropdown('installments');
        $installmentfield->addOption('full','FULL PAYMENT');
        $installmentfield->addOption('install','INSTALLMENT');


//complete payment
        $paymentlabel=new label('Complete payment');
        $paymentfield = new textinput('amount');



//amount payable by student
        $payablelabel=new label('Amount payable');
        $payablefield = new textinput('payable');
      

        $saveButton=new button('register');
        $saveButton->setToSubmit();
        $saveButton->value='Save details';


        $objTable = new htmlTable();
        
  //first row
        $objTable->startRow();
        
        $objTable->addCell($fname->show().':');
        $objTable->addCell($fnamefield->show());

        $objTable->addCell($lname->show().':');
        $objTable->addCell($lnamefield->show());

        $objTable->addCell($mname->show().':');
        $objTable->addCell($mnamefield->show());

        $objTable->endRow();
        
//second row
        $objTable->startRow();

        $objTable->addCell($classlabel->show().':');
        $objTable->addCell($classfield->show());

        $objTable->addCell($streamlabel->show().':');
        $objTable->addCell($streamfield->show());

        $objTable->addCell('');
        $objTable->addCell('');

        $objTable->endRow();
        
//third row
        $objTable->startRow();

        $objTable->addCell($banklabel->show().':');
        $objTable->addCell($bankfield->show());

        $objTable->addCell($branchlabel->show().':');
        $objTable->addCell($branchfield->show());

        $objTable->addCell('');
        $objTable->addCell('');
        
        $objTable->endRow();


//fourth row
        $objTable->startRow();

        $objTable->addCell($paymentlabel->show().':');
        $objTable->addCell($paymentfield->show());

        $objTable->addCell($installmntlabel->show().':');
        $objTable->addCell($installmentfield->show());

        $objTable->addCell('');
        $objTable->addCell('');
        
        $objTable->endRow();

//fifth row
        $objTable->startRow();

        $objTable->addCell($datelabel->show().':');
        $objTable->addCell($datepiki->show());

        $objTable->addCell('');
        $objTable->addCell('');

        $objTable->addCell('');
        $objTable->addCell('');

       $objTable->endRow();

//sixth row
        $objTable->startRow();

        $objTable->addCell($payablelabel->show().':');
        $objTable->addCell($payablefield->show());

        $objTable->addCell('');
        $objTable->addCell('');

        $objTable->addCell('');
        $objTable->addCell('');

        $objTable->endRow();

        $objTable->startRow();

        $objTable->addCell('');
        $objTable->addCell('');

        $objTable->addCell('');
        $objTable->addCell('');

        $objTable->addCell('');
        $objTable->addCell($saveButton->show());
        
        $objTable->endRow();

        $objform->addToForm($objTable->show());
        
        $objfield = new fieldset();
        $objfield->width=900;
        $objfield->align='center';

        $objfield->addContent($objform->show());


        echo $objfield->show();

    }
    function getAction() {
        $action=$this->getParam('action','edit');
        if($action=='edit')
            $formAction=  $this->uri(array('action'=>'edit'),'tzschoolfees');

        else
            $formAction=$this->uri (array('action'=>'add'),'tzschoolfees');
        return $formAction;
    }

    public function setValues($valuesArray=NULL) {

    }
    function show() {

        return $this->buildForm();

    }


}



?>
