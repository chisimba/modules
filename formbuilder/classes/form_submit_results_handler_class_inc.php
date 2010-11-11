<?php

class form_submit_results_handler extends object {

    private $formNumber;
    private $objDBFormSubmitResults;
    private $numberofSubmissionElements;
    private $noOfEntriesInPaginationBatch;
    private $initialEntryOffset;

    public function init() {
        $this->formNumber = NULL;
        $this->noOfEntriesInPaginationBatch = NULL;
        $this->initialEntryOffset = NULL;
        $this->objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results', 'formbuilder');
        $this->loadClass('form', 'htmlelements');
//Load the form class
        $this->loadClass('link', 'htmlelements');

    }

    public function setFormNumber($formNumber) {
        $this->formNumber = $formNumber;
    }

    public function getFormNumber() {
        return $this->formNumber;
    }

    public function setNumberOfSubmissionElements() {
        $submitResultsParameters = $this->objDBFormSubmitResults->getAllFormResults($this->formNumber);
        $formNumber = $submitResultsParameters['0']['formnumber'];
        $submitNumber = $submitResultsParameters['0']["submitnumber"];
        $this->numberofSubmissionElements = $this->objDBFormSubmitResults->getNumberofSubmissionElements($formNumber, $submitNumber);
    }

    public function setNumberOfEntriesInPaginationBatch($noOfEntries=2) {
        $this->noOfEntriesInPaginationBatch = $noOfEntries;
    }

    public function getNumberOfEntriesInPaginationBatch() {

        return $this->noOfEntriesInPaginationBatch;
    }

    public function getParticularSubmitResult($submitNumber) {

        $submitResultsParameters = $this->objDBFormSubmitResults->getParticularSubmitResults($submitNumber);
        $userIDOfFormSubmitter = $submitResultsParameters["0"]["useridofformsubmitter"];
        $timeOfSubmission = $submitResultsParameters["0"]["timeofsubmission"];


        $resultsContent = "<b>Name of Person Submitting Form:  </b>" . $this->objDBFormSubmitResults->getSubmitUsersFullName($userIDOfFormSubmitter) . "<br>";
        $resultsContent .= "<b>Email Address of Person Submitting Form:  </b>" . $this->objDBFormSubmitResults->getSubmitUsersEmail($userIDOfFormSubmitter) . "<br>";
        $resultsContent .= "<b>Time of Submission:  </b>" . $timeOfSubmission . "<br>";
        $resultsContent .= "<h2>Results</h2>";

        foreach ($submitResultsParameters as $thisSubmitResultParameter) {
            $formNumber = $thisSubmitResultParameter['formnumber'];
            $submitNumber = $thisSubmitResultParameter["submitnumber"];
            $formElementName = $thisSubmitResultParameter["formelementname"];
            $formElementType = $thisSubmitResultParameter["formelementtype"];
            $formElementValue = $thisSubmitResultParameter["formelementvalue"];
            $userIDOfFormSubmitter = $thisSubmitResultParameter["useridofformsubmitter"];
            $timeOfSubmission = $thisSubmitResultParameter["timeofsubmission"];

            $resultsContent .= "<b>" . $formElementType . " : " . $formElementName . " : </b><br>" . $formElementValue . "<br>";
        }
        return $resultsContent;
    }

    private function determinePaginationEntryOffset($paginationRequestNumber) {
        return $this->noOfEntriesInPaginationBatch * $paginationRequestNumber;
    }

    public function getMoreResultsPerPaginationRequest($latestOrAllResultsChoice = "allResults", $paginationRequestNumber) {
        if ($latestOrAllResultsChoice == "allResults") {
            $submitResultsParameters = $this->objDBFormSubmitResults->getOnlyDistinctFormResults($this->formNumber, $this->getNumberOfEntriesInPaginationBatch(), $this->determinePaginationEntryOffset($paginationRequestNumber));
        }
        if ($latestOrAllResultsChoice == "latestResults") {
            $submitResultsParameters = $this->objDBFormSubmitResults->getLatestSubmitResult($this->formNumber, $this->getNumberOfEntriesInPaginationBatch(), $this->determinePaginationEntryOffset($paginationRequestNumber));
        }

        if (empty($submitResultsParameters)) {
            return 0;
        }
        $resultsTable = &$this->newObject("htmltable", "htmlelements");
//Define the table border
        $resultsTable->border = 0;
//Set the table spacing
        $resultsTable->cellspacing = '12';
//Set the table width
        $resultsTable->width = "87%";

        foreach ($submitResultsParameters as $thisSubmitResultParameter) {
            $formNumber = $thisSubmitResultParameter['formnumber'];
            $submitNumber = $thisSubmitResultParameter["submitnumber"];
            $formElementName = $thisSubmitResultParameter["formelementname"];
            $formElementType = $thisSubmitResultParameter["formelementtype"];
            $formElementValue = $thisSubmitResultParameter["formelementvalue"];
            $userIDOfFormSubmitter = $thisSubmitResultParameter["useridofformsubmitter"];
            $timeOfSubmission = $thisSubmitResultParameter["timeofsubmission"];

            $viewResultsSelect = $this->getObject('geticon', 'htmlelements');

            $viewResultsSelect->setIcon('view');
            $viewResultsSelect->alt = "View Submitted Results";

            $mngViewResultsLink = new link($this->uri(array(
                                'module' => 'formbuilder',
                                'action' => 'viewSubmitNumberResult',
                                'submitNumber' => $submitNumber
                            )));
            if ($latestOrAllResultsChoice == "allResults") {
                $mngViewResultsLink->title = "viewParticularResult";
            }
            if ($latestOrAllResultsChoice == "latestResults") {
                $mngViewResultsLink->title = "viewLatestParticularResult";
            }

            $mngViewResultsLink->link = $viewResultsSelect->show();
            $linkViewResultManage = $mngViewResultsLink->show();

            $resultsTable->startRow();
            $resultsTable->addCell($this->objDBFormSubmitResults->getSubmitUsersFullName($userIDOfFormSubmitter));
            $resultsTable->addCell($this->objDBFormSubmitResults->getSubmitUsersEmail($userIDOfFormSubmitter));
            $resultsTable->addCell($timeOfSubmission);
            $resultsTable->addCell($linkViewResultManage);
            $resultsTable->endRow();

        }

        return $resultsTable->show();
    }

//public function setIntitialEntryOffset($entryOffset)
//{
//    $this->initialEntryOffset=$entryOffset;
//}
//
//public function getInitialEntryOffset()
//{
//    return $this->initialEntryOffset;
//}

    public function getAllFormResults($latestOrAllResultsChoice = "allResults") {
        if ($latestOrAllResultsChoice == "allResults") {
            $submitResultsParameters = $this->objDBFormSubmitResults->getOnlyDistinctFormResults($this->formNumber, $this->getNumberOfEntriesInPaginationBatch(), 0);
        }
        if ($latestOrAllResultsChoice == "latestResults") {
            $submitResultsParameters = $this->objDBFormSubmitResults->getLatestSubmitResult($this->formNumber, $this->getNumberOfEntriesInPaginationBatch(), 0);
        }

if ($submitResultsParameters ==  NULL)
{
 return "No results have been submitted for this form yet.";
}
        $resultsTable = &$this->newObject("htmltable", "htmlelements");
//Define the table border
        $resultsTable->border = 0;
//Set the table spacing
        $resultsTable->cellspacing = '12';
//Set the table width
        $resultsTable->width = "95%";

//Create the array for the table header
        $tableHeader = array();
        $tableHeader[] = "Name of Submitter";
        $tableHeader[] = "Email of Submitter";
        $tableHeader[] = "Time of Submission";
        $tableHeader[] = "View Result";
        $resultsTable->addHeader($tableHeader, "heading");



        foreach ($submitResultsParameters as $thisSubmitResultParameter) {
            $formNumber = $thisSubmitResultParameter['formnumber'];
            $submitNumber = $thisSubmitResultParameter["submitnumber"];
            $formElementName = $thisSubmitResultParameter["formelementname"];
            $formElementType = $thisSubmitResultParameter["formelementtype"];
            $formElementValue = $thisSubmitResultParameter["formelementvalue"];
            $userIDOfFormSubmitter = $thisSubmitResultParameter["useridofformsubmitter"];
            $timeOfSubmission = $thisSubmitResultParameter["timeofsubmission"];


            $viewResultsSelect = $this->getObject('geticon', 'htmlelements');

            $viewResultsSelect->setIcon('view');
            $viewResultsSelect->alt = "View Submitted Results";

            $mngViewResultsLink = new link($this->uri(array(
                                'module' => 'formbuilder',
                                'action' => 'viewSubmitNumberResult',
                                'submitNumber' => $submitNumber
                            )));
            if ($latestOrAllResultsChoice == "allResults") {
                $mngViewResultsLink->title = "viewParticularResult";
            }
            if ($latestOrAllResultsChoice == "latestResults") {
                $mngViewResultsLink->title = "viewLatestParticularResult";
            }

            $mngViewResultsLink->link = $viewResultsSelect->show();
            $linkViewResultManage = $mngViewResultsLink->show();

            $resultsTable->startRow();
            $resultsTable->addCell($this->objDBFormSubmitResults->getSubmitUsersFullName($userIDOfFormSubmitter));
            $resultsTable->addCell($this->objDBFormSubmitResults->getSubmitUsersEmail($userIDOfFormSubmitter));
            $resultsTable->addCell($timeOfSubmission);
            $resultsTable->addCell($linkViewResultManage);
            $resultsTable->endRow();
        }

        return $resultsTable->show();
    }

}
?>
