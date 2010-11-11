<?php

$formNumber=$this->getParam("formNumber",NULL);
if (isset($formNumber))
{


$objPublishingMenuConstructor = $this->getObject('view_form_list','formbuilder');

$objPublishingMenuConstructor->getPublishingFormParameters($formNumber);
echo "form nuimber is".$formNumber;

?>
<div id="publishingFormOption">
        <?php
 echo $objPublishingMenuConstructor->showFormPublishingIndicator();
    ?>
    </div>


	<div id="simple">
		<?php
                 echo $objPublishingMenuConstructor->showSimplePublishingForm();
                ?>
	</div>
	<div id="advanced">
		<?php
                 echo $objPublishingMenuConstructor->showAdvancedPublishingForm();
                 }
                ?>
	</div>