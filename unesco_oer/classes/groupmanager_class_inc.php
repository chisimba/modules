<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of groupmanager_class_inc
 *
 * @author davidwaf
 */
class groupmanager extends object {

    public function init() {
        $this->objForum = & $this->getObject('dbforum', 'forum');
      
  
    }

    public function saveForum($id, $groupName, $groupDesc) {
        $forum_context = $id;
        $forum_workgroup = '';
        $forum_name = $groupName;
        $forum_description = $groupDesc;
        $defaultForum = 'N';
        $forum_visible = 'Y';
        $forumLocked = 'N';
        $ratingsenabled = 'N';
        $studentstarttopic = 'Y';
        $attachments = 'Y';
        $subscriptions = 'Y';
        // Needs to be worked on
        $moderation = 'N';
        $forum = $this->objForum->insertSingle($forum_context, $forum_workgroup, $forum_name, $forum_description, $defaultForum, $forum_visible, $forumLocked, $ratingsenabled, $studentstarttopic, $attachments, $subscriptions, $moderation);
    }


    public function AddOerResource($groupid){

    }




  
}

?>
