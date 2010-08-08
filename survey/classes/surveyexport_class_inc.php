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
        $questions = $this->objQuestion->listQuestions($surveyId);
        $responses = $this->objResponse->listResponses($surveyId);
    }
}
