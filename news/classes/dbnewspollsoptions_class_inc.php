<?php

class dbnewspollsoptions extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_polls_options');
		$this->loadClass('link', 'htmlelements');
        
        
    }
    
    public function addOptions($poll, $options)
    {
        $this->delete('pollid', $poll);
        
        $counter = 1;
        
        foreach ($options as $option)
        {
            if ($option != '') {
                $this->addOption($poll, $option, $counter);
                $counter++;
            }
        }
    }
    
    private function addOption($poll, $option, $order)
    {
        return $this->insert(array('pollid'=>$poll, 'optionstr'=>$option, 'optionorder'=>$order));
    }
    
    public function getPollOptions($poll)
    {
        return $this->getAll(' WHERE pollid=\''.$poll.'\' ORDER BY optionorder');
    }
    


    

    
    

}
?>