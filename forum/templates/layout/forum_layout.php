<?php

$middleColumn = $this->getVar('middleColumn');
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);
$cssLayout->setMiddleColumnContent($middleColumn);
// Display the Layout
$objModule = $this->getObject('modules', 'modulecatalogue');
$isRegistered = $objModule->checkIfRegistered('oer');
if ($isRegistered) {
    echo '<div id="threecolumn">' . $cssLayout->show() . '</div>';
} else {
    echo $cssLayout->show();
}
?>