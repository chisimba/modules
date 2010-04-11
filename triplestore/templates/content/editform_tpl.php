<?php
$objTripleUi = $this->getObject('tripleui', 'triplestore');
echo "<br />";
echo $objTripleUi->buildEditForm("add");

$viewUrl = $this->uri(array('action' => 'getmytripples'), 'triplestore');
?>
<a href="<?php echo $viewUrl?>">View mine</a>