<?php
class giftops extends object {

    public function init() {
        /*impoting classes*/
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('htmltable','htmlelements');
    }

    public function createForm() {
        $donor       = new textinput('donor','','',127);
        $receiver    = new textinput('receiver','','',127);
        $description = new textarea('description','',15,50);
        $giftname    = new texinput('giftname','','',127);
        $value       = new textinput('value','','',15);
        $date        = new textinput('date','','',15);

        $form = new form('form',$this->uri(array('action'=>'submitform')));
        
    }

    public function getForm($id) {
        $form = $this->createForm();
        
    }
}
?>
