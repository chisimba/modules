<?php

class phirehoseops extends object
{
    public function init()
    {
        include_once $this->getResourcePath('Phirehose.php');
        $this->loadClass('phirehosestream', 'phirehose');
    }

    public function filter($username, $password, $keywords, $callback)
    {
        $stream = new phirehosestream($username, $password, Phirehose::METHOD_FILTER);
        $stream->setTrack($keywords);
        $stream->setCallback($callback);
        $stream->consume();
    }
}

?>
