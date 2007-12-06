<?php
/**
* Data class extends dbTable for tbl_worksheet_questions.
* @package worksheet
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
        die("You cannot view this page directly");
}


/**
* Model class for the table tbl_worksheet_questions.
* @author Tohir Solomons
* @author Megan Watson
* @copyright (c) 2004 UWC
* @package worksheet
* @version 0.2
*/
class dbworksheetquestions extends dbTable
{

    /**
    * Constructor method to define the table
    */
    public function init()
    {
        parent::init('tbl_worksheet_questions');
        $this->table='tbl_worksheet_questions';
    }

    /**
    * Save method for inserting or updating a record in the table.
    * @param string $mode Edit if mode is edit, else insert
    * @param string $userId The id of the current user.
    * @return
    */
    public function saveRecord($mode, $userId, $imageFile=NULL)
    {
        $question = string_replace('<p>','','question');
        $question .= string_replace('</p>','','question');
        $id=addslashes(TRIM($this->getParam('id', '')));
        $worksheet_id = addslashes(TRIM($this->getParam('worksheet_id', '')));        
        $question = addslashes(TRIM($this->getParam('question', '')));        
        $answer = $this->getParam('answer', '');
        $answer = string_replace('<p>','','answer');
        $answer = string_replace('</p>','',$answer);
        $question_worth = addslashes(TRIM($this->getParam('worth', '')));
        $question_order = addslashes(TRIM($this->getParam('order', '')));
        $dateLastUpdated = date('Y-m-d H:i:s');

        $array = array(
        'question' => $question,
        'model_answer' => $answer,
        'question_worth' => $question_worth,
        'userid' => $userId,
        'datelastupdated' => $dateLastUpdated);

        if(!is_null($imageFile) && !empty($imageFile)){
            $array['imageid'] = $imageFile['id'];
            $array['imagename'] = $imageFile['name'];
        }

        // if edit use update
        if ($mode=="edit") {
            $this->update("id", $id, $array);

        }
        if ($mode=="add") {
            $array['worksheet_id'] = $worksheet_id;
            $array['question_order'] = $question_order;
            $this->insert($array);

        }//if
    }//function

    /**
    * Method to insert a single question into the database.
    * @param string $worksheet_id The id of the worksheet being editted.
    * @param string $question The question being submitted.
    * @param string $answer The model answer to the question.
    * @param string $question_worth The marks allocated to the question.
    * @param string $question_order The position of the question in the worksheet.
    * @param string $userId The id of the creator
    * @param string $dateLastUpdated The time of editing
    * @return
    */
    public function insertSingle($worksheet_id, $question, $answer, $question_worth, $question_order, $userId, $dateLastUpdated, $imageId='', $imageName='')
    {
    		
        $id = $this->insert(array(
                'worksheet_id' => $worksheet_id,
                'question' => $question,
                'model_answer' => $answer,
                'question_worth' => $question_worth,
                'question_order' => $question_order,
                'userid' => $userId,
                'datelastupdated' => $dateLastUpdated,
                'updated' => date('Y-m-d H:i:s'),
                'imageid' => $imageId,
                'imagename' => $imageName));
        return $id;
    }

    /**
    * Method to delete a question.
    * The order of each of the questions following the deleted question is decreased by 1.
    * Delete image if set.
    * @param string $id The id of the question.
    * @return
    */
    public function deleteQuestion($id)
    {
        $sql = 'SELECT question_order, worksheet_id, imageId FROM '.$this->table;
        $sql .= " WHERE id='$id'";
        $result = $this->getArray($sql);

        if(!empty($result)){
            // Delete image if set
            if(!empty($result[0]['imageId'])){
                $objFile =& $this->getObject('dbfile','filemanager');
                //$objFile->changeTables('tbl_worksheet_filestore','tbl_worksheet_blob');
                $objFile->deleteFile($result[0]['imageid']);
            }

            // Reorder remaining questions
            $row = $this->getQuestions($result[0]['worksheet_id'], $result[0]['question_order'], FALSE);
            if(!empty($row)){
                foreach($row as $line){
                    $pos = $line['question_order']-1;
                    $this->update('id',$line['id'],array('question_order'=>$pos));
                }
            }
        }

        $this->delete('id',$id);
    }

    /**
    * Method to get the number of the last question in the worksheet.
    * @param string $worksheet_id The id of the current worksheet.
    * @return string $lastOrder The number of the last question in the worksheet.
    */
    public function getLastOrder($worksheet_id)
    {
        $sql = 'SELECT question_order FROM  tbl_worksheet_questions
        WHERE worksheet_id = "'.$worksheet_id.'"
        ORDER BY question_order DESC LIMIT 1';

        $results = $this->getArray($sql);

        if (count($results) == 0) {
            $lastOrder = 1;
        } else {
            $lastOrder = $results[0]['question_order']+1;
        }

        return $lastOrder;
    }

    /**
    * Method to get the number of questions in the worksheet.
    * @param string $worksheet_id The id of the current worksheet.
    * @return string $result The number of questions in the worksheet.
    */
    public function getNumQuestions($worksheet_id)
    {
        $sql = 'SELECT count( DISTINCT tbl_worksheet_questions.id ) AS questionCount
        FROM `tbl_worksheet_questions`
        INNER JOIN tbl_worksheet ON ( tbl_worksheet_questions.worksheet_id = tbl_worksheet.id )
        WHERE tbl_worksheet.id = "'.$worksheet_id.'" ';

        $result = $this->getArray($sql);    
 	      return $result[0]['questioncount'];
    }

    /**
    * Method to retrieve a maximum of 4 questions in a worksheet starting from the given position.
    * @param string $worksheet_id The id of the current worksheet.
    * @param int $order The position of the last question viewed.
    * @param bool $limit Flag to determine whether to limit the number of questions retrieved.
    * @return array $result The worksheet questions.
    */
    public function getQuestions($worksheet_id, $order=0, $limit=TRUE)
    {
        $sql = 'SELECT * FROM '.$this->table." WHERE worksheet_id='$worksheet_id' AND
        question_order>$order ORDER BY question_order";

        if($limit){
            $sql .= " LIMIT 4";
        }
        $result = $this->getArray($sql);
        return $result;
    }

    /**
    * Get a question from database
    * @param string $id The id of the required question.
    * @return array $rows The question details and the total number of questions.
    */
    public function getQuestion($id)
    {
        $sql = 'SELECT * FROM '.$this->table." WHERE id='$id'";
        $rows=$this->getArray($sql);

        if($rows){
            $rows[0]['num_questions']=$this->getNumQuestions($rows[0]['worksheet_id']);
            return $rows[0];
        }
        return FALSE;
    }

    /**
    * Change the order of questions in the worksheet
    * @param string $id The id of the question to be moved
    * @param bool $order If order is true move question up else move question down 1
    * @return bool TRUE if the order has been changed, FALSE if it hasn't.
    */
    public function changeOrder($id,$order)
    {
        $sql = 'SELECT worksheet_id, question_order FROM '.$this->table
        ." WHERE id='$id'";
        $rows=$this->getArray($sql);

        if($rows){
            $pos=$rows[0]['question_order'];
            $wid=$rows[0]['worksheet_id'];
            // if move question up, check its not the first question
            if($order && $pos>1){
                $newpos=$pos-1;
            // if move question down, check its not the last question
            }else if(!$order){
                    $num=$this->getNumQuestions($wid);
                    if($pos<$num){
                        $newpos=$pos+1;
                    }else{
                        return FALSE;
                    }
            }else{
                return FALSE;
            }
            // swap order of questions
            $sql = 'SELECT id FROM '.$this->table." WHERE worksheet_id='$wid' and question_order='$newpos'";
            $row=$this->getArray($sql);

            if($row){
                $this->update('id',$row[0]['id'],array('question_order'=>$pos));
                $this->update('id',$id,array('question_order'=>$newpos));
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
    * Method to get the first question in the worksheet for marking.
    * @param string $worksheet_id The id of the current worksheet.
    * @return array $data The details of the first question in the worksheet.
    */
    public function getFirstQuestion($worksheet_id)
    {
        $sql = 'SELECT * FROM '.$this->table."
        WHERE worksheet_id='$worksheet_id' ORDER BY question_order LIMIT 1";

        $data=$this->getArray($sql);

        $data[0]['count']=$this->getNumQuestions($worksheet_id);

        if($data){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to get the next question in the worksheet for marking.
    * @param string $worksheet_id The id of the current worksheet.
    * @return array $data The details of the last question in the worksheet.
    */
    public function getNextQuestion($worksheet_id,$order)
    {
        $sql = 'SELECT * FROM '.$this->table."
        WHERE worksheet_id='$worksheet_id' AND question_order='$order' LIMIT 1";

        $data=$this->getArray($sql);

        $data[0]['count']=$this->getNumQuestions($worksheet_id);

        if($data){
            return $data;
        }
        return FALSE;
    }
} //end of class
?>
