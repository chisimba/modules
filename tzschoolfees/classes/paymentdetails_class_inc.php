<?php
/* 
 * @author john richard
 * @module name:fee module
 * @date 2011 05 06
 * and open the template in the editor.
 */
//class which shows student payment details
class paymentdetails extends Object{
    public $lang;

function  init() {

    $this->lang=$this->getObject('language','language');
    
    }
    //function to load clases
function loadElements(){

    //load all clases needed
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
function buildForm(){
//call function loadElemnts
        $this->loadElements();

        //creating new objects
$objform = new form('payment',  $this->getAction());
$formlabel = new label('<h2>student payment details</h2><br>');
$objform->addToForm($formlabel->show());

//student full name

 $namelabel=new label('FULL NAME');
$objform->addToForm($namelabel->show());
$namefield = new textinput('fullname');
$objform->addToForm($namefield->show().'<br/>');

//student class

//$namelabel = new label($this->lang->languageText('mod_tzschoolfees_classname_label','tzschoolfees'),'class');
 $namelabel=new label('CLASS NAME');
$objform->addToForm($namelabel->show());
$classfield = new dropdown('class');
$classfield->addOption('f1','FORM ONE');
$classfield->addOption('f2','FORM TWO');
$classfield->addOption('f3','FORM THREE');
$classfield->addOption('f4','FORM FOUR');
$classfield->addOption('f5','FORM FIVE');
$classfield->addOption('f6','FORM SIX');
$objform->addToForm($classfield->show().'<br/>');

//amount paid by student
//$amountlabel = new label($this->lang->languageText('mod_tzschoolfees_amount_label','tzschoolfees'),'amount');
 $amountlabel=new label('AMOUNT PAID');
$objform->addToForm($amountlabel->show());
$amountfield = new textinput('amount');
$objform->addToForm($amountfield->show().'<br/>');

//installments by student
//$installmntlabel = new label($this->lang->languageText('mod_tzschoolfees_installments_label','tzschoolfees'),'installments');
 $installmntlabel=new label('INSTALLMENTS');
$objform->addToForm($installmntlabel->show());
$installmentfield = new dropdown('installments');
$installmentfield->addOption('full','FULL PAYMENT');
$installmentfield->addOption('install','INSTALLMENT');

$objform->addToForm($installmentfield->show().'<br/>');


//complete payment
//$paymentlabel = new label($this->lang->languageText('mod_tzschoolfees_payment_label','tzschoolfees'),'payment');
$paymentlabel=new label('COMPLETE PAYMENT');
$objform->addToForm($paymentlabel->show());
$paymentfield = new textinput('amount');
$objform->addToForm($paymentfield->show().'<br/>');


//amount payable by student
//$payablelabel = new label($this->lang->languageText('mod_tzschoolfees_payable_label','tzschoolfees'),'payable');
 $payablelabel=new label('AMOUNT PAYABLE');
$objform->addToForm($payablelabel->show());
$payablefield = new textinput('payable');
$objform->addToForm($payablefield->show().'<br/>');

 $saveButton=new button('register');
       // $saveButton=new button('save','Submit');
       $saveButton->setToSubmit();
       $saveButton->value='save details';

        $objform->addToForm($saveButton->showDefault());
        
$objfield = new fieldset();
$objfield->width=600;
$objfield->align='center';

$objfield->addContent($objform->show());


echo $objfield->show();

}
        function getAction(){
        $action=$this->getParam('action','edit');
        if($action=='edit')
            $formAction=  $this->uri(array('action'=>'edit'),'tzschoolfees');

        else
            $formAction=$this->uri (array('action'=>'add'),'tzschoolfees');
        return $formAction;
    }

        public function setValues($valuesArray=NULL){

        }
        function show(){

            return $this->buildForm();

        }


}



?>
