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
            $overviewTable->attributes = "rules=none frame=box";
            $overviewTable->cellspacing = '3';
            $overviewTable->width = "100%";
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
            $table->attributes = "rules=none frame=box";
            $table->cellspacing = '3';
            $table->width = "100%";

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
            $objTitles->str = "Rules And Syllabuses cont...";
            
            $table = $this->newObject('htmltable', 'htmlelements');
            $table->border = 1;
            $table->cellspacing = '3';
            $table->width = "90%";

            // Add the table heading.
            $table->startRow();
            $table->addHeaderCell($objTitles->show(), $width=null, $valign="top", $align='left', $class=null, $attrib="colspan='2'");
            $table->endRow();

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

}