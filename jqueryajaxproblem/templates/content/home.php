<?php
$this->loadClass('datepicker', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('inputmasks', 'htmlelements');

echo "This demo will prove that dynamically loading any chisimba entity
that uses a little but of javascript craps out. <BR><BR>";

echo "Load pure html elements through ajax works fine. Let us load a radio
element.<BR>";
$this->objButtonRadio = new button('buttonToLoadRadio');
$this->objButtonRadio->setValue('Load a Radio Element through ajax');
echo $this->objButtonRadio->show() . "<BR><BR>";


echo "This is a datepicker normally built in chisimba. Now I want to load another
one just like it through ajax. See Chisimba Break (See the fireworks.).";


$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'testdatepicker';
$datePicker->setDateFormat("Aug-06-1996");
$datePicker->setDefaultDate("2010/02/02");
echo $datePicker->show();

$this->objButton = new button('buttonToLoadDatePicker');
$this->objButton->setValue('Load another date picker through ajax ');
echo $this->objButton->show() . "<BR><BR>";

echo "This text input has a number masked input that works. It uses the 'inputmask' class.
Let us load a another one of these thorugh ajax.<BR>";
$objTextInput = new textinput("test");
$objTextInput->setCss('text input_mask mask_number');
$inputMasks = $this->getObject('inputmasks', 'htmlelements');
echo $constructedtextInput = $inputMasks->show() . $objTextInput->show()."<BR>";
$this->objButtonTI = new button('buttonToLoadTextInput');
$this->objButtonTI->setValue('Load a text Input Element through ajax');
echo $this->objButtonTI->show() . "<BR><BR>";
?>

<div id="tempdivcontainer"></div>

<script type="text/javascript">


    jQuery(document).ready(function() {

        jQuery(":input[name='buttonToLoadRadio']").click(function(){

            var DataToPost = {
                "testString": "TestRadio"
            };
            var myurlToProduceRadio = "<?php echo html_entity_decode($this->uri(array('action'=>'produceRadio'),'jqueryajaxproblem')); ?>";
            jQuery('#tempdivcontainer').load(myurlToProduceRadio, DataToPost ,function postSuccessFunction(html) {
            });
        });


        jQuery(":input[name='buttonToLoadDatePicker']").click(function(){

            var DataToPost = {
                "testString": "This is a test string"
            };
            var myurlToProduceDatePicker = "<?php echo html_entity_decode($this->uri(array('action'=>'produceDatePicker'),'jqueryajaxproblem')); ?>";
            jQuery('#tempdivcontainer').load(myurlToProduceDatePicker, DataToPost ,function postSuccessFunction(html) {
            });
        });

        jQuery(":input[name='buttonToLoadTextInput']").click(function(){

            var DataToPost = {
                "testString": "TestTextInput"
            };
            var myurlToProduceTextInput = "<?php echo html_entity_decode($this->uri(array('action'=>'produceTextInput'),'jqueryajaxproblem')); ?>";
            jQuery('#tempdivcontainer').load(myurlToProduceTextInput, DataToPost ,function postSuccessFunction(html) {
            });
        });
    });

</script>