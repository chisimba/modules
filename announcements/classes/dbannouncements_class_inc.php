<?php


/**
 * Short description for file
 * 
 * Long description (if any) ...
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
    }
    
    function addAnnouncement($title, $message, $type='site', $contexts=array(), $email=FALSE)
    {
        $messageId = $this->insert(array(
                'title' => $title,
                'message' => $message,
                'title' => $title,
                'createdon' => $this->now(),
                'createdby' => $this->objUser->userId(),
                'contextid' => $type,
                
            ));
        
        if ($type == 'site') {
            
            if ($messageId != FALSE) {
                // Add to Search
                $objIndexData = $this->getObject('indexdata', 'search');
                
                // Prep Data
                $docId = 'announcement_entry_'.$messageId;
                $docDate = $this->now();
                $url = $this->uri(array('action'=>'view', 'id'=>$messageId), 'announcements');
                $title = $title;
                $contents = $title.': '.$message;
                $teaser = $message;
                $module = 'announcements';
                $userId = $this->objUser->userId();
                $context = 'root';
                
                // Add to Index
                $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId, NULL, NULL, $context);
                
                if ($email) {
                    $this->sendEmail($title, $message, $this->getSiteRecipients());
                }
            }
            
        } else {
            
            if ($messageId != FALSE) {
                // Add to Search
                $objIndexData = $this->getObject('indexdata', 'search');
                
                // Prep Data
                $docId = 'announcement_entry_'.$messageId;
                $docDate = $this->now();
                $url = $this->uri(array('action'=>'view', 'id'=>$messageId), 'announcements');
                $title = $title;
                $contents = $title.': '.$message;
                $teaser = $message;
                $module = 'announcements';
                $userId = $this->objUser->userId();
                
                $emailList = array();
                
                foreach ($contexts as $context)
                {
                    $this->addMessageToContext($messageId, $context);
                    $emailList = array_merge($emailList, $this->getContextRecipients($contextCode));
                    
                    // Add to Index
                    $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId, NULL, NULL, $context);
                }
                
                if ($email) {
                    $this->sendEmail($title, $message, $this->getSiteRecipients());
                }
            }
        }
        
        return $messageId;
    }
    
    private function addMessageToContext($messageId, $context)
    {
        parent::init('tbl_announcements_context');
        
        $result = $this->insert(array('announcementid'=>$messageId, 'contextid'=>$context));
        
        parent::init('tbl_announcements');
        
        return $result;
    }
    
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
    
    private function getContextRecipients($contextCode)
    {
        $objGroups =  $this->getObject('managegroups', 'contextgroups');
        
        $lecturers = $objGroups->contextUsers('Lecturers', $contextCode, array('emailAddress'));
        $students = $objGroups->contextUsers('Students', $contextCode, array('emailAddress'));
        $guests = $objGroups->contextUsers('Guests', $contextCode, array('emailAddress'));
        
        return array_merge($lecturers, $students, $guests);
    }
    
    public function getMessage($id)
    {
        return $this->getRow('id', $id);
    }
    
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
    
    public function getNumContextAnnouncements($context)
    {
        
        $sql = "SELECT count( DISTINCT tbl_announcements.id ) AS recordcount FROM tbl_announcements
        INNER JOIN tbl_announcements_context ON ( tbl_announcements_context.announcementid = tbl_announcements.id )
        WHERE (tbl_announcements_context.contextid = '{$context}')
        ORDER BY createdon DESC ";
        
        $result = $this->getArray($sql);
        
        return $result[0]['recordcount'];
    }
    
    public function getMessageContexts($messageId)
    {
        parent::init('tbl_announcements_context');
        
        $result = $this->getAll(" WHERE announcementid = '{$messageId}' ");
        
        parent::init('tbl_announcements');
        
        return $result;
    }

}
?>