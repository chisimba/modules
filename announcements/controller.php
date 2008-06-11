<?php

/**
*
*
*/
class announcements extends controller
{


    private $userContext;
    
    private $lecturerContext;
    
    /**
    * Constructor for the Module
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objContext = $this->getObject('dbcontext','context');
        $this->objDate = $this->newObject('dateandtime', 'utilities');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        
        $this->objAnnouncements = $this->getObject('dbannouncements');
        
        $this->userId = $this->objUser->userId();
        
        $objUserContext = $this->getObject('usercontext', 'context');
        $this->userContext = $objUserContext->getUserContext($this->userId);
        $this->lecturerContext = $objUserContext->getContextWhereLecturer($this->userId);
        
        $this->isAdmin = $this->objUser->isAdmin();
        
        $this->itemsPerPage = 10;
        
        $this->setVar('lecturerContext', $this->lecturerContext);
        $this->setVar('isAdmin', $this->isAdmin);
    }



    /**
    * Standard Dispatch Function for Controller
    *
    * @access public
    * @param string $action Action being run
    * @return string Filename of template to be displayed
    */
    public function dispatch($action)
    {
        /*
        * Convert the action into a method (alternative to
        * using case selections)
        */
        $method = $this->getMethod($action);
        
        $this->setLayoutTemplate('announcements_layout_tpl.php');
        
        /*
        * Return the template determined by the method resulting
        * from action
        */
        return $this->$method();
    }



    /**
    *
    * Method to convert the action parameter into the name of
    * a method of this class.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return string the name of the method
    *
    */
    function getMethod(& $action)
    {
        if ($this->validAction($action)) {
            return '__'.$action;
        } else {
            return '__home';
        }
    }

    /**
    *
    * Method to check if a given action is a valid method
    * of this class preceded by double underscore (__). If it __action
    * is not a valid method it returns FALSE, if it is a valid method
    * of this class it returns TRUE.
    *
    * @access private
    * @param string $action The action parameter passed byref
    * @return boolean TRUE|FALSE
    *
    */
    function validAction(& $action)
    {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    // Beginning of Functions Relating to Actions in the Controller //



    /**
    *
    *
    */
    private function __home()
    {
        $numAnnouncements = $this->objAnnouncements->getNumAnnouncements($this->userContext);
        
        $this->setVarByRef('numAnnouncements', $numAnnouncements);
        return 'home_tpl.php';
    }
    
    private function __add()
    {
        $this->setVar('mode', 'add');
        
        return 'addedit_tpl.php';
    }
    
    
    private function __save()
    {
        $title = $this->getParam('title');
        $message = $this->getParam('message');
        $email = $this->getParam('email');
        $mode = $this->getParam('mode');
        $recipienttarget = $this->getParam('recipienttarget');
        $contexts = $this->getParam('contexts');
        
        if ($mode == 'add' && ($title == '' || strip_tags($message) == '')) {
            $this->setVar('mode', 'fixup');
            $this->setVar('lecturerContext', $this->lecturerContext);
            $this->setVar('isAdmin', $this->isAdmin);
            return 'addedit_tpl.php';
        } else if ($mode == 'add') {
            
            $result = $this->objAnnouncements->addAnnouncement($title, $message, $recipienttarget, $contexts);
            
            if ($result == FALSE) {
                
            } else {
                return $this->nextAction('view', array('id'=>$result));
            }
            
        }
    }
    
    private function __view()
    {
        $id = $this->getParam('id');
        
        $announcement = $this->objAnnouncements->getMessage($id);
        
        if ($announcement == FALSE) {
            return $this->nextAction(NULL, array('error'=>'unknownannouncement'));
        } else {
            $this->setVarByRef('announcement', $announcement);
            
            return 'view_tpl.php';
        }
    }
    
    private function __getajax()
    {
        $page = $this->getParam('page', 0);
        
        $announcements = $this->objAnnouncements->getAllAnnouncements($this->userContext, $this->itemsPerPage, $page);
        
        if (count($announcements) == 0) {
            echo '<div class="noRecordsMessage">There are no announcements</div>';
        } else {
            return $this->generateAjaxResponse($announcements);
        }
    }
    
    private function __getcontextajax()
    {
        $page = $this->getParam('page', 0);
        
        $announcements = $this->objAnnouncements->getContextAnnouncements($this->objContext->getContextCode(), $this->itemsPerPage, $page);
        
        if (count($announcements) == 0) {
            echo '<div class="noRecordsMessage">There are no announcements</div>';
        } else {
            return $this->generateAjaxResponse($announcements);
        }
    }
    
    private function generateAjaxResponse($announcements)
    {
        $this->loadClass('link', 'htmlelements');
            $this->loadClass('htmlheading', 'htmlelements');
            $objDateTime = $this->getObject('dateandtime', 'utilities');
            $objTrimString = $this->getObject('trimstr', 'strings');
            
            $table = $this->newObject('htmltable', 'htmlelements');
            $table->startHeaderRow();
            $table->addHeaderCell('Date');
            $table->addHeaderCell('Title');
            $table->addHeaderCell('By');
            $table->addHeaderCell('Type');
            $table->endHeaderRow();
            
            /*
            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();*/
            
            foreach ($announcements as $announcement)
            {
                $link = new link ($this->uri(array('action'=>'view', 'id'=>$announcement['id'])));
                $link->link = $announcement['title'];
                
                $table->startRow();
                $table->addCell($objDateTime->formatDate($announcement['createdon']), 150);
                $table->addCell($link->show());
                $table->addCell($this->objUser->fullName($announcement['createdby']), 200);
                
                if ($announcement['contextid'] == 'site') {
                    $type = 'Site';
                } else {
                    $type = 'Course';
                }
                
                $table->addCell($type, 200);
                $table->endRow();
                
                /*
                $table->startRow();
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->endRow();
                */
            }
            
            echo $table->show();
    }
}

?>