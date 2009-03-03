<?php
header("Content-Type: text/html;charset=utf-8");
$objImView = $this->getObject('jbviewer');
echo $objImView->renderOutputForBrowser($msgs);

exit;
