<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of storyparser_class_inc
 *
 * @author kim
 */
class dbtopics  extends dbTable {

    public function init() {
        parent::init('tbl_speak4free_topics');
        $this->table = 'tbl_speak4free_topics';
    }
    public function getTopics() {
        return $this->getAll('where active = "1"');
    }

    public function savetopic($title,$content,$active) {
        $data=array('title'=>$title,'content'=>$content,'active'=>$active);
        return $this->insert($data);
    }

    public function updatetopic($title,$content,$topicid,$active) {
        $data=array('title'=>$title,'content'=>$content,'active'=>$active);
        return $this->update('id',$topicid, $data);
    }
    public function getTitle($topicid) {
        return $this->getRow('id',$topicid);
    }

    public function getTopic($id) {
        return $this->getRow('id',$id);
    }
    public function deletetopic($id) {
        $groups=$this->getObject('dbgroups');
        $groups->deleteTopic($id);
        return $this->delete('id',$id);
    }
}
?>
