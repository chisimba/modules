<?php

/**
 * Announcements Table
 * 
 * This class contains a list of all db functions for the announcements module
 * 
 * PHP version unknow
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 

/* ----------- data class extends dbTable for tbl_blog------------*/
// security check - must be included in all scripts

/**
 * Description for $GLOBALS
 * @global integer $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}



class dbAnnouncements extends dbTable
{
    /**
     * Constructor method to define the table
     */
    public function init() 
    {
        parent::init('tbl_announcements');
        $this->objUser = $this->getObject('user', 'security');
        
        $this->objIndexData = $this->getObject('indexdata', 'search');
    }
    
    
    /**
     * Method to add an announcment
     * @param string $title Title of the Announcement
     * @param string $message Message of the Announcement
     * @param string $type Type of the Announcement - either site or context
     * @param array $contexts Type of the Announcement - If type is context, list of contexts announcement is for
     * @param boolean $email Should Announcement be emailed to users
     * @return string Insert Id
     */
    public function addAnnouncement($title, $message, $type='site', $contexts=array(), $email=FALSE)
    {
        // Do insert
        $messageId = $this->insert(array(
                'title' => $title,
                'message' => $message,
                'title' => $title,
                'createdon' => $this->now(),
                'createdby' => $this->objUser->userId(),
                'contextid' => $type,
                
            ));
        
        // If site announcement
        if ($type == 'site') {
            
            if ($messageId != FALSE) {
                
                // Add to Search
                $this->addAnnouncementToSearchIndex($messageId, $title, $message, 'root');
                
                // Optimize Search
                $this->objIndexData->optimize();
                
                if ($email) {
                    $this->sendEmail($title, $message, $this->getSiteRecipients());
                }
            }
            
        } else {
            
            if ($messageId != FALSE) {
                
                $emailList = array();
                
                foreach ($contexts as $context)
                {
                    $this->addMessageToContext($messageId, $context);
                    $emailList = array_merge($emailList, $this->getContextRecipients($context));
                    
                    $this->addAnnouncementToSearchIndex($id, $title, $message, $context);
                }
                
                // Optimize Search
                $this->objIndexData->optimize();
                
                if ($email) {
                    $this->sendEmail($title, $message, $this->getSiteRecipients());
                }
            }
        }
        
        return $messageId;
    }
    
    public function updateAnnouncement($id, $title, $message, $type='site', $contexts=array(), $email=FALSE)
    {
        if ($type == 'context') {
            $this->removeContextAnnouncement($id);
        }
        
        $result = $this->update('id', $id, array(
                'title' => $title,
                'message' => $message,
                'title' => $title,
                'createdon' => $this->now(),
                'createdby' => $this->objUser->userId(),
                'contextid' => $type,
            ));
        
        // If site announcement
        if ($type == 'site') {
            
            if ($result != FALSE) {
                // Add to Search
                $this->addAnnouncementToSearchIndex($id, $title, $message, 'root');
                
                // Optimize Search
                $this->objIndexData->optimize();
                
                if ($email) {
                    $this->sendEmail($title, $message, $this->getSiteRecipients());
                }
            }
            
        } else {
            
            if ($result != FALSE) {
                
                $emailList = array();
                
                foreach ($contexts as $context)
                {
                    $this->addMessageToContext($id, $context);
                    $emailList = array_merge($emailList, $this->getContextRecipients($context));
                    
                    $this->addAnnouncementToSearchIndex($id, $title, $message, $context);
                }
                
                // Optimize Search
                $this->objIndexData->optimize();
                
                if ($email) {
                    $this->sendEmail($title, $message, $this->getSiteRecipients());
                }
            }
        }
    }
    
    public function deleteAnnouncement($id)
    {
        $announcement = $this->getMessage($id);
        
        if ($announcement == FALSE) {
            return FALSE;
        } else {
            
            if ($announcement['contextid'] == 'context') {
                $this->removeContextAnnouncement($id);
            }
            
            $this->delete('id', $id);
            
            $this->objIndexData->removeIndex('announcement_entry_'.$id);
            
            return TRUE;
        }
    }
    
    
    /**
     * Method to remove a context announcement
     * This function removes the search entry as well as link to db record
     *
     * @param string $id Record Id of Announcement
     */
    private function removeContextAnnouncement($id)
    {
        parent::init('tbl_announcements_context');
        
        $result = $this->getAll(" WHERE announcementid = '{$id}' ");
        
        if (count($result) > 0) {
            foreach ($result as $context)
            {
                $this->objIndexData->removeIndex('announcement_entry_'.$context['contextid'].'_'.$id);
                $this->delete('id', $context['id']);
            }
        }
        
        
        parent::init('tbl_announcements');
    }
    
    private function addAnnouncementToSearchIndex($id, $title, $message, $context)
    {
        
        
        // Prep Data
        
        if ($context == 'root') {
            $docId = 'announcement_entry_'.$id;
        } else {
            $docId = 'announcement_entry_'.$context.'_'.$id;
        }
        
        $docDate = $this->now();
        $url = $this->uri(array('action'=>'view', 'id'=>$id), 'announcements');
        $title = $title;
        $contents = $title.': '.$message;
        $teaser = $message;
        $module = 'announcements';
        $userId = $this->objUser->userId();
        
        // Add to Index
        $this->objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId, NULL, NULL, $context, 'noworkgroup', NULL, NULL, NULL, NULL, FALSE); // Turn off optimizing
    }
    
    /**
     * Method to link an announcement to a context
     *
     * @param string $messageId Record Id of the message
     * @param string $context Context Code
     *
     * @return insert id
     */
    private function addMessageToContext($messageId, $context)
    {
        parent::init('tbl_announcements_context');
        
        $result = $this->insert(array('announcementid'=>$messageId, 'contextid'=>$context));
        
        parent::init('tbl_announcements');
        
        return $result;
    }
    
    /**
     * Method to email an announcement to users
     *
     * @param string $title Title of the announcement
     * @param string $message Message of the announceme nt
     * @param array $recipients List of Recipients (array of email addresses);
     */
    private function sendEmail($title, $message, $recipients)
    {
        $recipients = array_unique($recipients);
        
        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('from', $this->objUser->email());
        $objMailer->setValue('fromName', $this->objUser->fullname());
        $objMailer->setValue('subject', $title);
        $objMailer->setValue('bcc', $recipients);
        $objMailer->setValue('body', $message);
        
        $objMailer->send(TRUE);
    }
    
    /**
     * Method to get the list of email addresses of all users for site announcements
     * @return array Email Addresses of all Users
     */
    private function getSiteRecipients()
    {
        $users = $this->objUser->getAll();
        $emailList = array();
        
        foreach ($users as $user)
        {
            $emailList[] = $user['emailaddress'];
        }
        
        return $emailList;
    }
    
    /**
     * Method to get the list of email addresses for users belong to a context
     * @param string $contextCode Context Code
     * @return array Email Addresses of Context Users
     */
    private function getContextRecipients($contextCode)
    {
        $objGroups =  $this->getObject('managegroups', 'contextgroups');
        
        $lecturers = $objGroups->contextUsers('Lecturers', $contextCode, array('emailAddress'));
        $students = $objGroups->contextUsers('Students', $contextCode, array('emailAddress'));
        $guests = $objGroups->contextUsers('Guests', $contextCode, array('emailAddress'));
        
        return array_merge($lecturers, $students, $guests);
    }
    
    /**
     * Method to get an Announcement Message
     * @param string $id Record Id of Announcement
     * @return array Record Details
     */
    public function getMessage($id)
    {
        return $this->getRow('id', $id);
    }
    
    /**
     * Method to get a list of context announcements
     *
     * @param array $contexts List of contexts if required
     * @param int $limit Number of Results
     * @param int $page Page of Results
     *
     * @return array List of announcements
     */
    public function getAllAnnouncements($contexts, $limit=NULL, $page=NULL)
    {
        $where = '';
        
        if (count($contexts) > 0) {
            foreach($contexts as $context)
            {
                $where .= "tbl_announcements_context.contextid = '{$context}' OR ";
            }
        }
        
        $sql = "SELECT DISTINCT tbl_announcements.id, title, createdon, tbl_announcements.contextid, message, createdby FROM tbl_announcements
        LEFT JOIN tbl_announcements_context ON ( tbl_announcements_context.announcementid = tbl_announcements.id )
        WHERE ({$where} tbl_announcements.contextid = 'site')
        ORDER BY createdon DESC ";
        
        if ($limit != NULL && $page != NULL) {
            
            $page = $page * $limit;
            
            $sql .= " LIMIT {$page}, {$limit}";
        }
        
        //echo $sql;
        
        return $this->getArray($sql);
    }
    
    /**
     * Method to get a list of site announcements
     *
     * @param int $limit Number of Results
     * @param int $page Page of Results
     *
     * @return array List of announcements
     */
    public function getSiteAnnouncements($limit=NULL, $page=NULL)
    {
        
        $sql = "SELECT DISTINCT tbl_announcements.id, title, createdon, tbl_announcements.contextid, message, createdby FROM tbl_announcements
        WHERE (contextid = 'site')
        ORDER BY createdon DESC ";
        
        
        if (is_int($limit) && is_int($page)) {
            
            $sql .= " LIMIT {$page}, {$limit}";
        }
        
        //echo $sql;
        
        return $this->getArray($sql);
    }
    
    /**
     * Method to get a list of announcements for a particular context
     *
     * @param string $context Context Code
     * @param int $limit Number of Results
     * @param int $page Page of Results
     *
     * @return array List of announcements
     */
    public function getContextAnnouncements($context, $limit=NULL, $page=NULL)
    {
        
        $sql = "SELECT DISTINCT tbl_announcements.id, title, createdon, tbl_announcements.contextid, message, createdby FROM tbl_announcements
        LEFT JOIN tbl_announcements_context ON ( tbl_announcements_context.announcementid = tbl_announcements.id )
        WHERE (tbl_announcements_context.contextid = '{$context}')
        ORDER BY createdon DESC ";
        
        if ($limit != NULL && $page != NULL) {
            
            $page = $page * $limit;
            
            $sql .= " LIMIT {$page}, {$limit}";
        }
        
        //echo $sql;
        
        return $this->getArray($sql);
    }
    
    /**
     * Method to get the number of announcements in particular contexts
     *
     * @param array $contexts Context Codes
     * @return int Number of announcements
     */
    public function getNumAnnouncements($contexts)
    {
        $where = '';
        
        if (count($contexts) > 0) {
            foreach($contexts as $context)
            {
                $where .= "tbl_announcements_context.contextid = '{$context}' OR ";
            }
        }

        
        $sql = "SELECT count( DISTINCT tbl_announcements.id ) AS recordcount FROM tbl_announcements
        LEFT JOIN tbl_announcements_context ON ( tbl_announcements_context.announcementid = tbl_announcements.id )
        WHERE ({$where} tbl_announcements.contextid = 'site')
        ORDER BY createdon DESC ";
        
        $result = $this->getArray($sql);
        
        return $result[0]['recordcount'];
    }
    
    /**
     * Method to get the number of announcements in a particular context
     *
     * @param string $context Context Code
     * @return int Number of announcements
     */
    public function getNumContextAnnouncements($context)
    {
        
        $sql = "SELECT count( DISTINCT tbl_announcements.id ) AS recordcount FROM tbl_announcements
        INNER JOIN tbl_announcements_context ON ( tbl_announcements_context.announcementid = tbl_announcements.id )
        WHERE (tbl_announcements_context.contextid = '{$context}')
        ORDER BY createdon DESC ";
        
        $result = $this->getArray($sql);
        
        return $result[0]['recordcount'];
    }
    
    /**
     * Method to get the contexts a message is linked to
     * @param string $messageId Message Id
     * @return array List of Contexts
     */
    public function getMessageContexts($messageId)
    {
        parent::init('tbl_announcements_context');
        
        $result = $this->getAll(" WHERE announcementid = '{$messageId}' ");
        
        parent::init('tbl_announcements');
        
        $return = array();
        
        if (count($result) > 0) {
            foreach ($result as $context)
            {
                $return[] = $context['contextid'];
            }
        }
        
        return $return;
    }

}
?>