<?php
class alumni_installscripts extends dbTable
{
    public function init()
    {

	    parent::init('tbl_users');
    }

    public function preinstall($version = NULL)
    {
        switch($version)
        {
        default:
	    return $this->getAll();
        }
    }

    public function postinstall()
    {
	    return 'postinstall done';
    }
}
?>
