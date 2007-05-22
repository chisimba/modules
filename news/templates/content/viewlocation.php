<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'View Location';
echo $header->show();

print_r($location);

$objBuildMap = $this->getObject('simplebuildmap', 'simplemap');
$objBuildMap->gLat = $location['latitude'];
$objBuildMap->gLong = $location['longitude'];
$objBuildMap->magnify = $location['zoomlevel'];

echo $objBuildMap->show();

$this->setVar('pageSuppressXML', TRUE);
?>