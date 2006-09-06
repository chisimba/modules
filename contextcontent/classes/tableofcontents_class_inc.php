<?php

class tableofcontents extends object
{

    public function init()
    {
        $this->objContext =& $this->getObject('dbcontext', 'context');
        $this->contextCode = $this->objContext->getContextCode();
        $this->objContentOrder =& $this->getObject('db_contextcontent_order');
        
        $this->loadClass('link', 'htmlelements');
    }
    
    public function show()
    {
        $str = '<h3>Table of Contents</h3>';
        $str .= $this->objContentOrder->getTree($this->contextCode, 'htmllist', $this->getParam('id'));
        
        $this->objDT = &$this->getObject( 'decisiontable','decisiontable' );
        // Create the decision table for the current module
        $this->objDT->create('contextcontent');
        // Collect information from the database.
        $this->objDT->retrieve('contextcontent');
        // Test the current action being requested, to determine if it requires access control.
        if( $this->objDT->hasAction('addpage') ) {
            // Is the action allowed?
            if ($this->objDT->isValid('addpage') ) {
                // redirect and indicate the user does not have sufficient access.
                $link = new link ($this->uri(array('action'=>'addpage', 'context'=>$this->contextCode, 'id'=>$this->getParam('id'))));
                $link->link = 'Add New Page';
                $str .= '<p>'.$link->show().'</p>';
            }
        }
        
        
        
        $link = new link ($this->uri(array('action'=>'leavecontext'), 'context'));
        $link->link = 'Leave Course';
        $str .= '<p>'.$link->show().'</p>';
        
        return $str;
    }

}


?>