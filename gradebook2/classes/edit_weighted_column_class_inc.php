<?php
class edit_weighted_column extends object
{
    public function init()
    {
    }
    private function loadElements()
    {
        //Load the form class 
        $this->loadClass('form','htmlelements');
        //Load the textinput class 
        $this->loadClass('textinput','htmlelements');
        //Load the label class
        $this->loadClass('label', 'htmlelements');
        //Load the textarea class
        $this->loadClass('textarea','htmlelements');
        //Load the button class
        $this->loadClass('button', 'htmlelements'); 
        //Load the radio button class
        $this->loadClass('radio', 'htmlelements'); 
    }
    private function buildForm()
    {

        $this->loadElements();
    }
    private function getFormAction()
    {
    }
    public function show()
    {
    }
}
?>
