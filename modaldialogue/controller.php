<?php

class modaldialogue extends controller
{
    protected $objSkin;

    public function init()
    {
        $this->objSkin = $this->getObject('skin', 'skin');
    }
    public function dispatch()
    {
        $this->objSkin->setVar('SUPPRESS_PROTOTYPE', true);
        $this->objSkin->setVar('JQUERY_VERSION', '1.2.6');
        return 'main_tpl.php';
    }
}
