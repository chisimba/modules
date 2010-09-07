<?php
echo "Test String is :".$testString = $_REQUEST['testString'];

$this->loadClass('radio','htmlelements');
$objElement = new radio($testString);
                $objElement->addOption('m','Male');
                $objElement->addOption('f','Female');
                $objElement->addOption('n','Seaweed');
                $objElement->setSelected('f');
                echo $objElement->show().'<br>';

?>
