<?php
        $this->loadClass('textinput','htmlelements');
         $this->loadClass('inputmasks', 'htmlelements');
echo "Test String is :".$testString = $_REQUEST['testString']."<BR>";

 $objTextInput = new textinput("anothertest");
 $objTextInput->setCss('text input_mask mask_number');
    $inputMasks = $this->getObject('inputmasks', 'htmlelements');
echo $constructedtextInput = $inputMasks->show().$objTextInput->show()."<br>";
echo "The input mask functionality ceases to exist indicating that the
    inputmask class has crapped out."
?>
