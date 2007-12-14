<?php

class dbnewspolls extends dbtable
{

    public function init()
    {
        parent::init('tbl_news_polls');
		$this->loadClass('link', 'htmlelements');
        
        
    }
    
    public function getPolls()
    {
        return $this->getAll(' ORDER BY datecreated DESC');
    }

    
    public function addPoll($question)
    {
        $result = $this->insert(array(
            'pollquestion'=>$question,
            'datecreated' => strftime('%Y-%m-%d %H:%M:%S', mktime())
        ));
        
        if ($result != FALSE) {
            $polls = $this->getAll();
            
            foreach ($polls as $poll)
            {
                $this->update('id', $poll['id'], array('pollactive'=>'N'));
            }
            
            $this->update('id', $result, array('pollactive'=>'Y'));
            
        }
        
        return $result;
    }
    
    public function getLatestPoll()
    {
        $results = $this->getAll(' WHERE pollactive=\'Y\' ORDER BY datecreated DESC LIMIT 1');
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    public function getPollResults($poll)
    {
        $sql = ' SELECT * , (

SELECT count( vote )
FROM tbl_news_polls_votes
WHERE tbl_news_polls_votes.vote = tbl_news_polls_options.id
GROUP BY vote
) AS pollcount
FROM tbl_news_polls_options WHERE tbl_news_polls_options.pollid=\''.$poll.'\'';

        
        return $this->getArray($sql);
    }
    
    public function showPollResults($poll)
    {
        $results = $this->getPollResults($poll);
        
        $table = $this->newObject('htmltable', 'htmlelements');
        
        $table->startHeaderRow();
        $table->addHeaderCell('Option');
        $table->addHeaderCell('Votes');
        $table->addHeaderCell('Percentage');
        $table->addHeaderCell('&nbsp;');
        $table->endHeaderRow();
        
        $totalVotes = 0;
        
        foreach ($results as $result)
        {
            $totalVotes += $result['pollcount'] != '' ? $result['pollcount'] : 0;
        }
        
        
        $colors = array('blue', 'red', 'green');
        $count = 0;
        
        foreach ($results as $result)
        {
            $table->startRow();
            $table->addCell(htmlentities($result['optionstr']), 130);
            
            $pollcount = $result['pollcount'] != '' ? $result['pollcount'] : 0;
            
            $percentage = ($pollcount/$totalVotes*100);
            
            $table->addCell($pollcount, 50, NULL, 'center');
            $table->addCell(round($percentage, 2).'%', 90, NULL, 'center');
            
            if ($pollcount == 0) {
                $graph = '';
            } else {
                $objIcon = $this->getObject('geticon', 'htmlelements');
                $color = '_'.$colors[$count];
                
                if ($color == '_blue') {
                    $color = '';
                }
                
                $objIcon->setIcon('bar_left'.$color);
                $objIcon->alt = '';
                $objIcon->title = '';
                
                $graph = $objIcon->show();
                
                $width = explode('.', $percentage*4);
                $width = $width[0];
                
                $objIcon->setIcon('bar'.$color);
                $objIcon->extra = ' width="'.$width.'" height="16"';
                $graph .= $objIcon->show();
                
                $objIcon->setIcon('bar_right'.$color);
                $objIcon->extra = '';
                $graph .= $objIcon->show();
            
            }
            $table->addCell($graph);
            $table->endRow();
            
            $count++;
        }
        
        return $table->show();
        
    }
    
    public function showPollMiniResults($poll)
    {
        $results = $this->getPollResults($poll);
        
        $table = $this->newObject('htmltable', 'htmlelements');
        
        $totalVotes = 0;
        
        foreach ($results as $result)
        {
            $totalVotes += $result['pollcount'] != '' ? $result['pollcount'] : 0;
        }
        
        
        $colors = array('blue', 'red', 'green');
        $count = 0;
        
        foreach ($results as $result)
        {
            
            
            $pollcount = $result['pollcount'] != '' ? $result['pollcount'] : 0;
            
            $percentage = ($pollcount/$totalVotes*100);
            
            
            //$table->addCell(round($percentage, 2).'%', 90, NULL, 'center');
            
            if ($pollcount == 0) {
                $graph = '';
            } else {
                $objIcon = $this->getObject('geticon', 'htmlelements');
                $color = '_'.$colors[$count];
                
                if ($color == '_blue') {
                    $color = '';
                }
                
                $objIcon->setIcon('bar_left'.$color);
                $objIcon->alt = '';
                $objIcon->title = '';
                
                $graph = $objIcon->show();
                
                $width = explode('.', $percentage*1.5);
                $width = $width[0];
                
                $objIcon->setIcon('bar'.$color);
                $objIcon->extra = ' width="'.round($width, 0).'" height="16"';
                $graph .= $objIcon->show();
                
                $objIcon->setIcon('bar_right'.$color);
                $objIcon->extra = '';
                $graph .= $objIcon->show();
            
            }
            
            $table->startRow();
            $table->addCell(htmlentities($result['optionstr']).' ('.round($percentage, 2).'%)');
            $table->endRow();
            $table->startRow();
            $table->addCell($graph);
            $table->endRow();
            
            $count++;
        }
        
        return $table->show();
        
    }
    
    public function getPollFromOption($option)
    {
        $results = $this->getAll(' WHERE id = (SELECT pollid FROM tbl_news_polls_options WHERE tbl_news_polls_options.id=\''.$option.'\') LIMIT 1');
        
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results[0];
        }
    }
    
    public function pollImage($id)
    {
        require_once($this->getResourcePath('phpie/phPie.class.php'));
        
        $results = $this->getPollResults($id);
        
        //echo '<pre>';
        //print_r($results);
        
        $phpie = new phPie;
        $phpie->data = array();
        $phpie->width= 150;
        $phpie->height= 200;
        srand(time());
        foreach ($results as $option) {
            $phpie->AddItem($option['optionstr'], $option['pollcount']);
        }
        //$phpie->Legend         =FALSE;//(bool) @$_REQUEST['Legend'];
        $phpie->LegendOnSlices = (bool) @$_REQUEST['LegendOnSlices'];
        $phpie->DisplayPieChart();

    }

    

    
    

}
?>