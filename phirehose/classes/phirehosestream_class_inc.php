<?php

class phirehosestream extends Phirehose
{
    protected $callback;

    public function enqueueStatus($json)
    {
        $data = json_decode($json);
        call_user_func($this->callback, $data);
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
    }
}

?>
