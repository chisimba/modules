<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


class modulelinks_forum extends object
{

    public function init()
    {
        $this->loadClass('treenode','tree');
        $this->objForum =& $this->getObject('dbforum', 'forum');
        $this->objTopics =& $this->getObject('dbtopic', 'forum');
        $this->objPost =& $this->getObject('dbpost', 'forum');
    }
    
    public function show()
    {
        $rootNode = new treenode (array('link'=>$this->uri(NULL, 'forum'), 'text'=>'Forum', 'preview'=>'sffas'));
        
        $nodesArray = array();
        
        $forums = $this->objForum->getContextForums();
        
        foreach ($forums as $forum)
        {
            $node =& new treenode(array('link'=>$this->uri(array('action'=>'forum', 'id'=>$forum['id'])), 'text'=>$forum['forum_name']));
            
            $nodesArray['forum_'.$forum['id']] =& $node;
            $rootNode->addItem($nodesArray['forum_'.$forum['id']]);
            
            $topics = $this->objTopics->showTopicsInForum($forum['id'], '1');
            
            foreach ($topics as $topic)
            {
                $node =& new treenode(array('link'=>$this->uri(array('action'=>'viewtopic', 'id'=>$topic['topic_id'])), 'text'=>$topic['post_title']));
                
                $nodesArray['topic_'.$topic['topic_id']] =& $node;
                $nodesArray['post_'.$topic['first_post']] =& $node;
                $nodesArray['forum_'.$topic['forum_id']]->addItem($nodesArray['topic_'.$topic['topic_id']]);
                
                $posts = $this->objPost->getThread($topic['topic_id']);
                
                foreach ($posts as $post)
                {
                    
                    
                    if ($post['post_parent'] != '0') {
                        $node =& new treenode(array('link'=>$this->uri(array('action'=>'viewtopic', 'id'=>$post['topic_id'], 'post'=>$post['post_id'])), 'text'=>$post['post_title']));
                
                        $nodesArray['post_'.$post['post_id']] =& $node;
                        $nodesArray['post_'.$post['post_parent']]->addItem($nodesArray['post_'.$post['post_id']]);
                    }
                }
            }

        }

        
        return $rootNode;
    }
    
    /**
     * 
     *Method to get a set of links for a context
     *@param string $contextCode
     *@return array
     * @access public
     */
    public function getContextLinks($contextCode)
    { 
//       
//          $catId = $this->_objDBCategories->getCatId('context', $contextCode);
//         
//          $events =  $this->_objDBEventsCalendar->getAll('WHERE catid="'.$catId.'" ORDER BY event_date' );
//          
//          $bigArr = array();
//         
//          foreach ($events as $event)
//          {
//              $newArr = array();    
//              $newArr['menutext'] = $event['title'];
//              $newArr['description'] = $event['description'];
//              $newArr['itemid'] = $event['id'];
//              $newArr['moduleid'] = 'eventscalendar';
//              $newArr['params'] = array('month' => date('m',$event['event_date']),'action' => 'events');
//              $bigArr[] = $newArr;
//          }
//          
//          return $bigArr;
    }
}
?>
