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
        
    }
    /**
     * Return all records in the tbl_announcements.
     * 
     * @param $userId is the id taken from the tbl_user
     */
    public function listAll($contextid,$start) 
    {       
        $userrec = $this->getAll("WHERE contextid = '$contextid' ORDER BY createdon DESC LIMIT $start,5 ");
        
    return $userrec;
    }
    public function getLastRow($contextid) 
    {       
        $rec = $this->getAll("WHERE contextid = '$contextid' ORDER BY createdon DESC LIMIT 0,1 ");
        
    return $rec;
    }
    
     public function showList($linkAll = FALSE)
    {
        
        $this->objLanguage = $this->newObject('language', 'language');
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
        $this->objContext = $this->getObject('dbcontext','context');
        $blocktitle = $this->objLanguage->languageText('mod_announcements_latest','announcements');
        
        //get context so that only messages for this context are displayed
		$isInContext=$this->objContext->isInContext();
		 if($isInContext)
  		 {
   		 $this->contextCode=$this->objContext->getContextCode();
   		 $this->contextid=$this->objContext->getField('id',$this->contextCode);
   		 }
                 else
                 $this->contextid="root";

		
		
		$contextid=$this->contextid;
		
        
        $list = $this->listAll($contextid,0);
        $this->loadClass('link', 'htmlelements');

        $str = '';
        if(!empty($list)){
            foreach($list as $item){

                    $lncat = $item['title'];
                    $objLink = new link($this->uri(array('action' => '', 'id' => $item['id'])));
                    $objLink->link = $item['title'];
                    $objLink->style = "color: #0000BB;";
                    $lncat = $objLink->show();
                
                $str .= '<p style="margin: 0px;">'.$lncat.'</p>';
            }
        }

        $link = new link($this->uri(array(
            'action' => 'archive'
        )));
        $link->link = $this->objLanguage->languageText('mod_announcements_archive', 'announcements');
        $archive = $link->show();
        $str .= '<br><p style="margin: 0px;">'.$archive.'</p>';
        if($dispType == 'nobox'){
            return $str;
        }
        
        return $str; //$this->objFeatureBox->show($blocktitle, $str);
    }
     public function showQuickPost($contextPuid = '', $linkAll = FALSE)
    {
        $this->objLanguage = $this->newObject('language', 'language');
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
        $this->objContext = $this->getObject('dbcontext','context');
        $cform = new form('announcements', $this->uri(array(
    'action' => 'add'
        )));
        //start a fieldset
        $cfieldset = $this->getObject('fieldset', 'htmlelements');
        $ct = $this->newObject('htmltable', 'htmlelements');
        $ct->cellpadding = 5;
        //value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_announcements_title', 'announcements') . ':', 'input_cvalue');
        $ct->addCell($ctvlabel->show());
        $ct->EndRow();
        $ct->startRow();
        $ctv = new textinput('title',NULL,35,29);
        $ct->addCell($ctv->show());
        $ct->endRow();
        //value textfield
        $ct->startRow();
        $ctvlabel = new label($this->objLanguage->languageText('mod_announcements_message', 'announcements') . ':', 'input_cvalue');
        $ct->addCell($ctvlabel->show());
        $ct->endRow();
        
        $ct->startRow();
        $ctv = new textarea('message',NULL,5,25);
        $ct->addCell($ctv->show());
        $ct->endRow();
        
        
        
        //end off the form and add the button
        $this->objconvButton = new button($this->objLanguage->languageText('mod_announcements_add', 'announcements'));
        $this->objconvButton->setValue($this->objLanguage->languageText('mod_announcements_add', 'announcements'));
        $this->objconvButton->setToSubmit();
        $cfieldset->addContent($ct->show());
        $cform->addToForm($cfieldset->show());
        $cform->addToForm($this->objconvButton->show());
        $str = $cform->show();

        if($dispType == 'nobox'){
            return $str;
        }
        $blocktitle=$this->objLanguage->languageText('mod_announcements_quickadd', 'announcements');
        
        return $str; // $this->objFeatureBox->show($blocktitle, $str);
    }
    /**
     * Return a single record in the tbl_announcements.
     *
     * @param $id is the id taken from the tbl_announcements
     */
    public function listSingle($id) 
    {
        $onerec = $this->getRow('id', $id);
        return $onerec;
    }
    /**
     * Insert a record in the tbl_announcements.
     *
     * @param $userId         is the id taken from the tbl_user
     * @param $title      is the name taken from the form
     * @param $message       is the surname taken from the form
     * @param $createdBy   is the id taken from the tbl_user
     * @param $createdOn     take from date function now
     * @param $courseId		 is the id taken from the form from tbl_courses
     
     *                           
     *                           Also checks if text inputs are empty and returns the add a record template
     */
    public function insertRecord($title, $message, $createdon, $createdby,$contextid) 
    {
        
        $this->objUser = $this->getObject('user', 'security');
        $arrayOfRecords = array(
            'createdBy' => $this->objUser->userId() ,
            'title' => $title,
            'message' => $message,
            'createdOn' => $this->now(),
		'contextid' => $contextid,
            
        );
        if (empty($title) && empty($message)) {
            return "add_tpl.php";
        } else {
            return $this->insert($arrayOfRecords, 'tbl_announcements');
        }
    }
    /**
     * Deletes a record from the tbl_announcements
     *
     * @param $id is the generated id for a single record
     */
    public function deleteRec($id) 
    {
        return $this->delete('id', $id, 'tbl_announcements');
    }
    /**
     * Updates a record to the tbl_announcements
     *
     * @param $id             is the generated id for a single record
     * @param $arrayOfRecords is an array of all the information added in the form
     *                           
     */
    public function updateRec($id, $arrayOfRecords) 
    {
        return $this->update('id', $id, $arrayOfRecords, 'tbl_announcements');
    }
}
?>
