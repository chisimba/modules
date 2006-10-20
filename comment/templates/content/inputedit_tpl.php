<?php
$this->objInput =  & $this->getObject("commentinterface");
$tableName = $this->getParam('tableName', NULL);
$sourceId =$this->getParam('sourceId', NULL);
$moduleCode = $this->getParam('moduleCode', NULL);
$id = $this->getParam('id', NULL);

echo $this->objInput->renderInputEdit($tableName, $sourceId, $moduleCode, $id);
?>
