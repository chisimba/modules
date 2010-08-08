<?php

/**
 * Survey Export Helper Class
 * 
 * Exports a survey to formats such as CSV.
 * 
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
 * @category  Chisimba
 * @package   survey
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
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
// end security check

/**
 * Survey Export Helper Class
 * 
 * Exports a survey to formats such as CSV.
 * 
 * @category  Chisimba
 * @package   survey
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 */
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
        header("Content-Disposition: filename=$surveyId.csv");
        header('Content-Type: text/csv; charset=UTF-8');

        $answers   = array();
        $headings  = array('Response ID');
        $output    = fopen('php://output', 'w');
        $questions = $this->objQuestion->listQuestions($surveyId);
        $responses = $this->objResponse->listResponses($surveyId);
        $values    = array();

        foreach ($questions as $question) {
            $answers[$question['id']] = $this->objAnswer->listRows($question['id']);
            $headings[]               = preg_replace('/\s+/', ' ', trim($question['question_text']));
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

                       if (is_array($values[$question['id']])) {
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
