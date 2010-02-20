<?php

class phirehoseops extends object
{
    public function init()
    {
        include_once $this->getResourcePath('Phirehose.php');
        $this->loadClass('phirehosestream', 'phirehose');
    }

    public function track($username, $password, $keywords, $callback)
    {
        $stream = new phirehosestream($username, $password, Phirehoselib::METHOD_FILTER);
        $stream->setTrack($keywords);
        $stream->setCallback($callback);
        $stream->consume();
    }
}

?>
