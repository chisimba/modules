<?php

/**
* File modulelinks extends object
*
* @author Paul Mungai
* @copyright (c) 2009 UWC
* @version 0.1
*/



/* -------------------- manageviews_essay class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

class manageviews_essay extends object
{

    public function init()
    {
        // Get instances of the module classes
        $this->dbessays= $this->getObject('dbessays');
        $this->dbtopic= $this->getObject('dbessay_topics');
        $this->dbbook= $this->getObject('dbessay_book');
        // Get an instance of the user object
        $this->objUser= $this->getObject('user','security');
        // Get an instance of the context object
        $this->objContext= $this->getObject('dbcontext','context');

        // get user details
        $this->userId=$this->objUser->userId();
        // check if in context, and get code & title
        if($this->objContext->isInContext()){
            $this->contextcode=$this->objContext->getContextCode();
            $this->context=$this->objContext->getTitle();
            $incontext=TRUE;
        }else{
            $incontext=FALSE;
        }        
    }
    /**
    * Method to get booked and submitted essays for a student.
    * @return array $data The students essays
    **/
    public function getStudentEssays($contextcode=Null)
    {
    /**************** import data ********************/
        // get student booked essays
        if(empty($contextcode)){
        $data=$this->dbbook->getBooking("where context='".$this->contextcode
        ."' and studentid='".$this->userId."'");
	}else{
        $data=$this->dbbook->getBooking("where context='".$contextcode
        ."' and studentid='".$this->userId."'");
	var_dump($data);
	}

        if($data){
            foreach($data as $key=>$item){
	            //var_dump($item);
                // get essay info: topic, num
                $essay=$this->dbessays->getEssay($item['essayid'],'id, topic');
                //var_dump($essay);

                $data[$key]['essay']=$essay[0]['topic'];
                //var_dump($data[$key]);


                // get topic info: closing date
                $topic=$this->dbtopic->getTopic($item['topicid'],'name, closing_date, bypass');

                $data[$key]['name']=$topic[0]['name'];
                $data[$key]['date']=$topic[0]['closing_date'];
                if($topic[0]['bypass']){
                    $data[$key]['bypass']='YES';
                }else{
                    $data[$key]['bypass']='NO';
                }

                // get booking info: check if submitted or marked
                if(!empty($item['studentfileid'])){
                    $data[$key]['mark']=$item['mark'];
                }else{
                    $data[$key]['mark']='submit';
                }
                //var_dump($data[$key]);
            }
        }

    /**************** return data *******************/
        return $data;
     }   
}    

