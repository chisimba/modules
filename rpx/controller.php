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
        return 'main_tpl.php';
    }
}
