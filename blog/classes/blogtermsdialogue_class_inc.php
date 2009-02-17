<?php

class blogtermsdialogue extends jqdialogue
{
    public function init()
    {
    }

    public function show()
    {
        $this->content .= 'Test';
        return parent::show();
    }
}
