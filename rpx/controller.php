<?php

class rpx extends controller
{
    protected $objConfig;

    public function init()
    {
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    public function dispatch()
    {
        return 'main_tpl.php';
    }
}
