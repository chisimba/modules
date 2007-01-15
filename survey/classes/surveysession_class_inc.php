<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
die( "You cannot view this page directly" );
}
/**
*
* @copyright (c) 2000-2005, Kewl.NextGen ( http://kngforge.uwc.ac.za )
* @package survey
* @version 0.1
* @since 20 September 2005
* @author Kevin Cyster
*/

/**
* The session class is responsible for processing and managing the
* sessions of the survey manager
*
* @author Kevin Cyster
*/
class surveysession extends object
{
    // -------------- survey session methods -------------//
    /**
    * Method for adding survey data to the session variable.
    *
    * @access public
    * @param array $arrSurveyData The survey data
    * @return
    */
    public function addSurveyData($arrSurveyData)
    {
        $this->setSession('survey',$arrSurveyData);
    }

    // -------------- error session methods -------------//
    /**
    * Method for adding error data to the session variable.
    *
    * @access public
    * @param array $errorMessages The error messages
    * @return
    */
    public function addErrorData($errorMessages)
    {
        $this->setSession('error',$errorMessages);
    }

    // -------------- question session methods -------------//
    /**
    * Method for adding question data to the session variable.
    *
    * @access public
    * @param array $arrQuestionData The question data
    * @return
    */
    public function addQuestionData($arrQuestionData)
    {
        $this->setSession('question',$arrQuestionData);
    }

    // -------------- question row session methods -------------//
    /**
    * Method for adding question row data to the session variable.
    *
    * @access public
    * @param array $arrRowData The question row data
    * @return
    */
    public function addRowData($arrRowData)
    {
        $this->setSession('row',$arrRowData);
    }

    /**
    * Method for adding deleted question row ids to the session variable.
    *
    * @access public
    * @param string $rowId The question row id
    * @return
    */
    public function addDeletedRowId($rowId)
    {
        $arrRowId=$this->getSession('deletedRows');
        $arrRowId[]=$rowId;
        $this->setSession('deletedRows',$arrRowId);
    }

    // -------------- question column session methods -------------//
    /**
    * Method for adding question column data to the session variable.
    *
    * @access public
    * @param array $arrColumnData The question column data
    * @return
    */
    public function addColumnData($arrColumnData)
    {
        $this->setSession('column',$arrColumnData);
    }

    /**
    * Method for adding deleted question column ids to the session variable.
    *
    * @access public
    * @param string $columnId The question column id
    * @return
    */
    public function addDeletedColumnId($columnId)
    {
        $arrColumnId=$this->getSession('deletedColumns');
        $arrColumnId[]=$columnId;
        $this->setSession('deletedColumns',$arrColumnId);
    }

    // -------------- take survey session methods -------------//
    /**
    * Method for adding answer data to the session variable.
    *
    * @access public
    * @param array $arrAnswerData The answer data array
    * @return
    */
    public function addAnswerData($arrAnswerData)
    {
        $this->setSession('answer',$arrAnswerData);
    }

    // -------------- survey pages session methods -------------//
    /**
    * Method for adding answer data to the session variable.
    *
    * @access public
    * @param array $arrAnswerData The answer data array
    * @return
    */
    public function addPageData($arrPageData)
    {
        $this->setSession('page',$arrPageData);
    }

    /**
    * Method for adding deleted survey page ids to the session variable.
    *
    * @access public
    * @param string $pageId The survey page id
    * @return
    */
    public function addDeletedPageId($pageId)
    {
        $arrPageId=$this->getSession('deletedPages');
        $arrPageId[]=$pageId;
        $this->setSession('deletedPages',$arrPageId);
    }

    // -------------- delete sessions method -------------//
    /**
    * Method for deleteing question column data from the session variable.
    *
    * @access public
    * @param array $sessions The sessions to delete
    * @return
    */
    public function deleteSessionData($sessions)
    {
        foreach($sessions as $session){
            $this->unsetSession($session);
        }
    }
}
?>