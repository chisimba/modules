<?php
/**
* Download content template for essay.
* @package essay
*/
$objConfig = $this->getObject('altconfig', 'config');
$objFiles = $this->getObject('dbfile','filemanager');
$objCleanUrl = $this->getObject('cleanurl','filemanager');

$file = $objFiles->getFileInfo($this->getParam('fileid'));
if ($file == FALSE) {
    throw customException('File does not exist');
}
$filePath = $objConfig->getcontentPath().$file['path'];
$objCleanUrl->cleanUpUrl($filePath);
if (file_exists($filePath)) {
    header("Location: {$filePath}");
} else {
    throw customException('File does not exist');
}
?>