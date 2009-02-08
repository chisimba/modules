<?php

class modaldialogue extends controller
{
    protected $objJqDialogue;

    public function init()
    {
        $this->objJqDialogue = $this->getObject('jqdialogue', 'modaldialogue');
    }

    public function dispatch()
    {
        return 'main_tpl.php';
    }
}
