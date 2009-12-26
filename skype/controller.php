<?php

class skype extends controller
{
    protected $status;

    public function init()
    {
        $this->status = $this->getObject('skypestatus', 'skype');
    }

    public function dispatch()
    {
        $username = $this->getParam('username');
        if (strlen($username)) {
            $status = $this->status->getStatus($username);

            if (is_array($status)) {
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode($status);
            } else {
                header('HTTP/1.1 503 Service Unavailable');
            }
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    public function requiresLogin($action)
    {
        return FALSE;
    }
}
