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

        //load all clases needed
        $this->loadClass('htmlTable','htmlelements');
        $this->loadClass('form','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('label','htmlelements');
        $this->loadClass('button','htmlelements');
        //$this->loadClass('datepicker','htmlelements');
        //$this->loadClass('tetxarea','htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('fieldset','htmlelements');

    }
//function for building a form
    function buildForm() {
//call function loadElemnts
        $this->loadElements();

//creating new objects

        $objform = new form('payment',  $this->getAction());
        $formlabel = new label('<h2>student payment details</h2><br>');

        $objform->addToForm($formlabel->show());


//student full name

        $namelabel1=new label('FULL NAME');

        $namefield = new textinput('fullname');

//student class
        $namelabel=new label('CLASS NAME');


        $classfield = new dropdown('class');
        $classfield->addOption('f1','FORM ONE');
        $classfield->addOption('f2','FORM TWO');
        $classfield->addOption('f3','FORM THREE');
        $classfield->addOption('f4','FORM FOUR');
        $classfield->addOption('f5','FORM FIVE');
        $classfield->addOption('f6','FORM SIX');


//amount paid by student
        $amountlabel=new label('AMOUNT PAID');


        $amountfield = new textinput('amount');
//$objform->addToForm($amountfield->show().'<br/>');

//installments by student
        $installmntlabel=new label('INSTALLMENTS');


        $installmentfield = new dropdown('installments');
        $installmentfield->addOption('full','FULL PAYMENT');
        $installmentfield->addOption('install','INSTALLMENT');


//complete payment
        $paymentlabel=new label('COMPLETE PAYMENT');
        $paymentfield = new textinput('amount');



//amount payable by student
        $payablelabel=new label('AMOUNT PAYABLE');
        $payablefield = new textinput('payable');
      

        $saveButton=new button('register');
        $saveButton->setToSubmit();
        $saveButton->value='save details';


        $objTable = new htmlTable();
        
        $objTable->startRow();
        $objTable->addCell($namelabel1->show().':');
        $objTable->addCell($namefield->show());
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($namelabel->show().':');
        $objTable->addCell($classfield->show());
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($amountlabel->show().':');
        $objTable->addCell($amountfield->show());
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($installmntlabel->show().':');
        $objTable->addCell($installmentfield->show());
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($paymentlabel->show().':');
        $objTable->addCell($paymentfield->show());
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell($payablelabel->show().':');
        $objTable->addCell($payablefield->show());
        $objTable->endRow();

        $objTable->startRow();
        $objTable->addCell('');
        $objTable->addCell($saveButton->show());
        $objTable->endRow();

        $objform->addToForm($objTable->show());
        
        $objfield = new fieldset();
        $objfield->width=500;
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
