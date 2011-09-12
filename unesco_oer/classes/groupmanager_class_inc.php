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
        $forum_context = 'root';
        $forum_workgroup = $id;
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


        /**
     * Method to check that all required fields are entered
     * @param array $checkFields List of fields to check
     * @return boolean Whether all fields are entered or not
     */
    function checkFields($checkFields) {
        $allFieldsOk = TRUE;
        $this->messages = array();
        foreach ($checkFields as $field) {
            if ($field == '') {
                $allFieldsOk = FALSE;
            }
        }
        return $allFieldsOk;
    }


    public function AddOerResource() {
        $groupid = $this->getParam('groupid');
        $resource_name = $this->getParam('resource_name');
        $resource_type = $this->getParam('resource_type');
        $author = $this->getParam('resource_author');
        $publisher = $this->getParam('resource_publisher');
        $file = $this->getParam('group_name');

        $checkFields = array(
            $resource_name,
            $resource_type,
            $author,
            $publisher,
            $file
        );

        $problems = array();
        if (!$this->__checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }
        if (count($problems) > 0) {
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'addOERform_tpl.php';
        } else {
            $OERresource = $this->objDbOERresources->addGroupOerResource($groupid, $resource_name, $resource_type, $author, $publisher, $file);
        }
    }



  
}

?>
