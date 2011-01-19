<?php
    $bannerimagepath = 'skins/elsiskin/';
    $action = $this->getParam('action');
    if(!empty($action)) {
        $action = $this->getParam('action');
    }
    else {
        $action = 'home';
    }
    $objRotatingIdentity = $this->getObject('rotatingidentity', 'elsiskin');
    $objRotatingIdentity->setSkinPath($bannerimagepath);
    echo $objRotatingIdentity->show($action);
    echo'<!-- Start: Content -->';
    $objContent = $this->getObject('elsicontent', 'elsiskin');
    $objContent->setSkinPath($bannerimagepath);
    echo $objContent->show($action);
?>