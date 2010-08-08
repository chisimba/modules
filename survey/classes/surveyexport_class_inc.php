<?php

class surveyexport extends object
{
    private $objAnswer;
    private $objQuestion;
    private $objQuestionRow;
    private $objResponse;

    public function init()
    {
        $this->objAnswer      = $this->getObject('dbanswer');
        $this->objQuestion    = $this->getObject('dbquestion');
        $this->objQuestionRow = $this->getObject('dbquestionrow');
        $this->objResponse    = $this->getObject('dbresponse');
    }

    public function getCSV($surveyId)
    {
        $answers   = array();
        $headings  = array();
        $output    = fopen('php://output', 'w');
        $questions = $this->objQuestion->listQuestions($surveyId);
        $responses = $this->objResponse->listResponses($surveyId);
        $values    = array();

        foreach ($questions as $question) {
            $answers[$question['id']] = $this->objAnswer->listRows($question['id']);
            $headings[]               = $question['question_text'];
            $values[$question['id']]  = $this->objQuestionRow->listQuestionRows($question['id']);
        }

        fputcsv($output, $headings);

        foreach ($responses as $response) {
            $row = array();

            foreach ($questions as $question) {
               foreach ($answers[$question['id']] as $answer) {
                   if ($answer['response_id'] == $response['id']) {
                       if (array_key_exists($question['id'], $values)) {
                           foreach ($values[$question['id']] as $value) {
                               if ($value['row_order'] == $answer['answer_given']) {
                                   $row[] = $value['row_text'];
                                    break;
                               }
                           }
                       } else {
                           $row[] = $answer['answer_given'];
                       }
                       break;
                   } 
               }
            }

            fputcsv($output, $row);
        }

        fclose($output);
    }
}
