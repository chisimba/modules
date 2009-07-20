<?php

class rpx extends controller
{
    public $objConfig;

    public function init()
    {
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    public function dispatch()
    {
        return 'main_tpl.php';
    }
}
