<?php

$objImView = $this->getObject('imviewer', 'im');
echo $objImView->renderOutputForBrowser($msgs);

exit;
