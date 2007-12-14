<?php

class dbnewspollsvotes extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_polls_votes');
    }
    
    function saveVote($vote)
    {
        return $this->insert(array(
                'vote' => $vote,
                'ipaddress' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknownip',
                'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
            ));
    }
    


    

    
    

}
?>