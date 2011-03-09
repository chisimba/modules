<?php

/**
 * This class is used to format the data that goes into the pdf for printing apo
 * documents.
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
 * 59 Temple Place - Suite 3"50%", Boston, MA  02111-1"50%"7, USA.
 *
 * @category  Chisimba
 * @package   apo (document management system)
 * @author    Nguni Phakela, david wafula
 * @copyright 2010
  =
 */
if (!
        /**
         * Description for $GLOBALS
         * @global string $GLOBALS['kewl_entry_point_run']
         * @name   $kewl_entry_point_run
         */
        $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class formatting extends object {
    /*
     * Constructor
     */

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
    }

    public function getOviewviewTable($overview) {
        $objOverviewTitle = $this->getObject('htmlheading', 'htmlelements');
        $objOverviewTitle->str = 2;

        if (!empty($overview)) {
            $overviewTable = &$this->newObject("htmltable", "htmlelements");
            $overviewTable->border = 1;
            //$overviewTable->attributes = "rules=none frame=box";
            $overviewTable->cellspacing = '3';
            $overviewTable->width = "90%";
            /* $contactTable->border = 1;
              $contactTable->attributes = "rules=none frame=box";
              $contactTable->cellspacing = '3';
              $contactTable->width = "100%";
              // Add the table heading.
              $contactTable->startRow();
              $contactTable->addHeaderCell($objcontactTitles->show() , $width = null, $valign = "top", $align = 'left', $class = 'odd', $attrib = Null);
              $contactTable->endRow(); */

            $overviewTable->startRow();
            $overviewTable->addCell("A.1. Name of course/unit:");
            $overviewTable->addCell($overview['a1']);
            $overviewTable->endRow();

            $overviewTable->startRow();
            $overviewTable->addCell("A.2. This is a:");
            $overviewTable->addCell($overview['a2']);
            $overviewTable->endRow();

            $overviewTable->startRow();
            $overviewTable->addCell("A.3. Provide a brief motivation for the introduction/amendment of the course/unit:");
            $overviewTable->addCell($overview['a3']);
            $overviewTable->endRow();

            $overviewTable->startRow();
            $overviewTable->addCell("A.4. Towards which qualification(s) can the course/unit be taken?");
            $overviewTable->addCell($overview['a4']);
            $overviewTable->endRow();

            $overviewTable->startRow();
            $overviewTable->addCell("A.5. This new or amended course proposal is:");
            $overviewTable->addCell($overview['a5']);
            $overviewTable->endRow();
            
            $overviewLabel = $overviewTable->show() . '<br><br>';
            
            return $overviewLabel;
        }
    }

    public function getRulesAndSyllabusOne($rulesandsyllabus) {
        if (!empty($rulesandsyllabus)) {
            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            //$table->attributes = "rules=none frame=box";
            $table->cellspacing = '3';
            $table->width = "90%";

            $table->startRow();
            $table->addCell("B.1. How does this course/unit change the rules for the curriculum?");
            $table->addCell($rulesandsyllabus['b1']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.2. Describe the course/unit syllabus:");
            $table->addCell($rulesandsyllabus['b2']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.3. a. What are the pre-requisites for the course/unit if any?");
            $table->addCell($rulesandsyllabus['b3a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.3.b. What are the co-requisites for the course/unit if any?");
            $table->addCell($rulesandsyllabus['b3b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.4.a. This is:");
            $table->addCell($rulesandsyllabus['b4a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.4.b. If it is a compulsory course/unit, which course/unit is it replacing, or is the course/unit to be taken by students in addition to the current workload of courses/unit?");
            $table->addCell($rulesandsyllabus['b4b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.4.c. If it is both a compulsory and optional course/unit, provide details explaining for which qualifications/ programmes the course/unit would be optional and for which it would be compulsory:");
            $table->addCell($rulesandsyllabus['b4c']);
            $table->endRow();

            $tableLabel = $table->show(). "<br><br>";

            return $tableLabel;
        }
    }

    public function getRulesAndSyllabusTwo($rulesandsyllabus) {
        $objTitles = $this->getObject('htmlheading', 'htmlelements');
        if (!empty($rulesandsyllabus)) {
            //$objTitles->str = "Rules And Syllabuses cont...";
            
            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            $table->cellspacing = '3';
            $table->width = "90%";

            // Add the table heading.
            /*$table->startRow();
            $table->addHeaderCell($objTitles->show(), $width=null, $valign="top", $align='left', $class=null, $attrib="colspan='2'");
            $table->endRow();*/

            $table->startRow();
            $table->addCell("B.5.a. At what level is the course/unit taught?");
            $table->addCell($rulesandsyllabus['b5a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.5.b. In which year/s of study is the course/unit to be taught?");
            $table->addCell($rulesandsyllabus['b5b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.6.a. This is a:");
            $table->addCell($rulesandsyllabus['b6a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.6.b. If ‘other’, provide details of the course/unit duration and/or the number of lectures which comprise the course/unit:");
            $table->addCell($rulesandsyllabus['b6b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("B.6.c.Is the unit assessed:");
            $table->addCell($rulesandsyllabus['b6c']);
            $table->endRow();

            $tableLabel = $table->show(). "<br><br>";//echo $tableLabel;

            return $tableLabel;
        }
    }

    public function getSubsidyRequirements($subsidy) {
        if (!empty($subsidy)) {
            //$objTitles->str = "Rules And Syllabuses cont...";

            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            $table->cellspacing = '3';
            $table->width = "90%";

            // Add the table heading.
            /*$table->startRow();
            $table->addHeaderCell($objTitles->show(), $width=null, $valign="top", $align='left', $class=null, $attrib="colspan='2'");
            $table->endRow();*/

            $table->startRow();
            $table->addCell("C.1. The mode of instruction is understood to be contact/face-to-face lecturing. Provide details if any other mode of delivery is to be used:");
            $table->addCell($subsidy['c1']);
            $table->endRow();

            $table->startRow();
            $table->addCell("C.2.a. The course/unit is taught:");
            $table->addCell($subsidy['c2a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("C.2.b. If the course/unit is taught off-campus provide details:");
            $table->addCell($subsidy['c2b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("C.3. What is the third order CESM (Classification of Education Subject Matter) category for the course/unit? (The CESM manual can be downloaded from http://intranet.wits.ac.za/Academic/APO/CESMs.htm):");
            $table->addCell($subsidy['c3']);
            $table->endRow();

            $table->startRow();
            $table->addCell("C.4.a. Is any other School/Entity involved in teaching this unit?:");
            $table->addCell($subsidy['c4a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("C.4.b. If yes, state the name of the School/Entity:");
            $table->addCell($subsidy['c4b1']);
            $table->endRow();

            $table->startRow();
            $table->addCell("Percentage each teaches.:");
            $table->addCell($subsidy['c4b2']);
            $table->endRow();
            
            $tableLabel = $table->show(). "<br><br>";

            return $tableLabel;
        }
    }
    
    public function getOutcomesAndAssessmentsOne($outcomesandassessmentsone) {
        if (!empty($outcomesandassessmentsone)) {
            //$objTitles->str = "Rules And Syllabuses cont...";

            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            $table->cellspacing = '3';
            $table->width = "90%";

            // Add the table heading.
            /*$table->startRow();
            $table->addHeaderCell($objTitles->show(), $width=null, $valign="top", $align='left', $class=null, $attrib="colspan='2'");
            $table->endRow();*/

            $table->startRow();
            $table->addCell("D.1.a. On which OLD NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7 & 8) is the course/unit positioned?:");
            $table->addCell($outcomesandassessmentsone['d1a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("D.1.b. On which NEW NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7, 8, 9 & 10) is the course/unit positioned?:");
            $table->addCell($outcomesandassessmentsone['d1b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("D.2.a Specify the course/unit outcomes, assessment criteria and methods of assessment in the tables below.");
            $table->addCell($outcomesandassessmentsone['d2a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("D.2.b Learning Outcomes of the Course/Unit');");
            $table->addCell($outcomesandassessmentsone['d2b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("D.2.c Assessment Criteria for the Learning Outcomes");
            $table->addCell($outcomesandassessmentsone['d2c']);
            $table->endRow();

            $table->startRow();
            $table->addCell("D.3. How do the course/unit outcomes contribute to the acheivement of the overall qualification/programme outcomes?:");
            $table->addCell($outcomesandassessmentsone['d3']);
            $table->endRow();

            $tableLabel = $table->show(). "<br><br>";

            return $tableLabel;
        }
    }

    public function getOutcomesAndAssessmentsTwo($outcomesandassessmentstwo) {
        /*
         * To finish. Currently, the logic does not make any sense on the form.
         *
         */

        if (!empty($outcomesandassessmentstwo)) {
            //$objTitles->str = "Rules And Syllabuses cont...";

            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            $table->cellspacing = '3';
            $table->width = "90%";

            // Add the table heading.
            /*$table->startRow();
            $table->addHeaderCell($objTitles->show(), $width=null, $valign="top", $align='left', $class=null, $attrib="colspan='2'");
            $table->endRow();*/

            $table->startRow();
            $table->addCell("D.4 Specify the critical cross-field outcomes (CCFOs) integrated into the course/unit using the list provided.:<br>");
            $table->addCell($outcomesandassessmentstwo['id1']);
            $table->endRow();

            $tableLabel = $table->show(). "<br><br>";

            return $tableLabel;
        }

    }

    public function getOutcomesAndAssessmentsThree($outcomesandassessmentsthree) {
        if (!empty($outcomesandassessmentsthree)) {
            //$objTitles->str = "Rules And Syllabuses cont...";

            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            $table->cellspacing = '3';
            $table->width = "90%";

            // Add the table heading.
            /*$table->startRow();
            $table->addHeaderCell($objTitles->show(), $width=null, $valign="top", $align='left', $class=null, $attrib="colspan='2'");
            $table->endRow();*/

            $table->startRow();
            $table->addCell("D.5. Specify the notional study hours expected for the duration of the course/unit using the spreadsheet provided."
                            , $width=null, $valign="top", $align=null, $class=null, $attrib="colspan=2",$border = '0');
            $table->endRow();

            $table->startRow();
            $table->addCell("a. Over how many weeks will this course run?");
            $table->addCell($outcomesandassessmentsthree['a']);
            $table->endRow();

            $table->startRow();
            $table->addCell("b. How many hours of teaching will a particular student experience for this specific course in a single week?");
            $table->addCell($outcomesandassessmentsthree['b']);
            $table->endRow();

            $table->startRow();
            $table->addCell("c. How many hours of tutorials will a particular student experience for this specific course in a single week?");
            $table->addCell($outcomesandassessmentsthree['c']);
            $table->endRow();

            $table->startRow();
            $table->addCell("d. How many lab hours will a particular student experience for this specific course in a single week? (Note: the assumption is that there is only one staff contact hour per lab, the remaining lab time is student self-study)");
            $table->addCell($outcomesandassessmentsthree['d']);
            $table->endRow();

            $table->startRow();
            $table->addCell("e. How many other contact sessions are there each week including periods used for testd or other assessments which have not been included in the number of lecture, tutorial or laboratory sessions.");
            $table->addCell($outcomesandassessmentsthree['e']);
            $table->endRow();

            $table->startRow();
            $table->addCell("f. For every hour of lectures or contact with a staff member, how many hours should the student spend studying by her/himself?");
            $table->addCell($outcomesandassessmentsthree['f']);
            $table->endRow();

            $table->startRow();
            $table->addCell("g. How many exams are there per year?");
            $table->addCell($outcomesandassessmentsthree['g']);
            $table->endRow();

            $table->startRow();
            $table->addCell("h. How long is each exam?");
            $table->addCell($outcomesandassessmentsthree['h']);
            $table->endRow();

            $table->startRow();
            $table->addCell("i. How many hours of preparation for the exams is the student expected to undertake?");
            $table->addCell($outcomesandassessmentsthree['i']);
            $table->endRow();

            $tableLabel = $table->show(). "<br><br>";

            return $tableLabel;
        }
    }


}