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

    public function CSV($surveyId)
    {
        header('Content-Type: text/csv; charset=UTF-8');

        $answers   = array();
        $headings  = array('Response ID');
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
            $row = array($response['id']);

            foreach ($questions as $question) {
               $answerFound = FALSE;

               foreach ($answers[$question['id']] as $answer) {
                   if ($answer['response_id'] == $response['id']) {
                       $answerFound = TRUE;
                       $valueFound  = FALSE;

                       if (array_key_exists($question['id'], $values)) {
                           foreach ($values[$question['id']] as $value) {
                               if ($value['row_order'] == $answer['answer_given']) {
                                   $row[]      = $value['row_text'];
                                   $valueFound = TRUE;

                                   break;
                               }
                           }
                       }

                       if (!$valueFound) {
                           $row[] = $answer['answer_given'];
                       }

                       break;
                   } 
               }

               if (!$answerFound) {
                   $row[] = 'N/A';
               }
            }

            fputcsv($output, $row);
        }

        fclose($output);
    }
}
