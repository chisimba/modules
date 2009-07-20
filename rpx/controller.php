<?php

class rpx extends controller
{
    protected $objAltConfig;

    public function init()
    {
        $this->objAltConfig = $this->getObject('altconfig', 'config');
    }

    public function dispatch()
    {
        $this->setLayoutTemplate('user_layout_tpl.php');
        return 'main_tpl.php';
    }
}
