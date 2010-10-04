<?php

class zend extends object
{
    public function init()
    {
        ini_set('include_path', ini_get('include_path').':'.$this->getResourcePath(''));
        include $this->getResourcePath('Zend/Loader/Autoloader.php');
        Zend_Loader_Autoloader::getInstance();
    }
}
