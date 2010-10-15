<?php

/**
 * @package mcqtests
 * @filesource
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class for providing access to the table tbl_test_question_matching in the database
 * @author Nguni Phakela
 *
 * @copyright (c) 2010 University of the Witwatersrand
 * @package mcqtests
 * @version 1.2
 */
class dbquestion_matching extends dbtable {
    /**
     * Method to construct the class and initialise the table.
     *
     * @access public
     * @return
     */
    public $table;
    public $objUser;
    public $userId;
    public $objQuestions;
    public $objMultiAnswers;

    public function init() {
        parent::init('tbl_test_question_matching');
        $this->table = 'tbl_test_question_matching';
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();        
        $this->objMultiAnswers = $this->getObject('dbquestion_multianswers', 'mcqtests');
    }

    public function addMatchingQuestions($id, $matchingQuestionData) {
        //insert into this table
        $questionData = array();
        $questionData['questionid'] = $id;
        foreach($matchingQuestionData['subquestions'] as $row) {
            $questionData['subquestions'] = $row;
            $this->insert($questionData);
        }
        
        $answerData = array();
        $answerData['questionid'] = $id;
        foreach($matchingQuestionData['subanswers'] as $row) {
            $answerData['answer'] = $row;
            $this->objMultiAnswers->addAnswers($answerData);
        }
    }

    public function updateMatchingQuestions($id, $matchingQuestionData) {echo "ID IS: $id";die();
        $this->delete('questionid', $id);
        $this->insert($id, $matchingQuestionData);
    }

    public function getMatchingQuestions($id) {
        $filter = "WHERE questionid='$id'";
        return $this->getAll($filter);
    }

    public function deleteQuestions($id) {
        $this->delete('questionid', $id);
    }
}
?>