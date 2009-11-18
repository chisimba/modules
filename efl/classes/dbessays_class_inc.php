<?php

 /**
 * This class provides functionality to access the essays table
 * PHP version 5
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

 * @author
 * @copyright  2009 AVOIR
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
    $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class dbessays extends dbtable
{
    function init(){
        $this->objUser=$this->getObject('user','security');
		parent::init('efl_essay table');
    }
	
	//add a student essay to database
	public function addstudentEssay($userid,$essayid,$title,$content,$date)
        {
            $data = array(
						  'userid' =>$userid,
						  'essayid' => $essayid,
						  'title' => $title,
                          'content' => $content,
						  'date' => $date
                         );

            return $this->insert($data);
        }
		
	//get saved student essays
	public function getstudentEssays()
        {
            $data=$this->getAll();
            return $data;
        }

    function getEssays(){
        return array(
            array('title'=>'test title','details'=>'details','preview'=>'Preview','edit'=>'Edit')
        );
    }
    function getSubmittedEssays(){
        return array(
            array('from'=>$this->objUser->fullname(), 'date'=>'2009/11/15'),
            array('from'=>$this->objUser->fullname(), 'date'=>'2009/11/16'),
            array('from'=>$this->objUser->fullname(),'date'=>'2009/11/17'),
            array('from'=>$this->objUser->fullname(),'date'=>'2009/11/18')
        );
    }

    function getTitle($essayid){
        return array('title'=> "test title");
    }
}
?>
