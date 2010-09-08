<?php

/**
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

 * @author Palesa Mokwena, Thato Selebogo, Mmbudzeni Vhengani
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

$table=$this->getObject('htmltable','htmlelements');
        $table->border='0';
        $table->cellspacing = '0';
        $table->width='941';
	$viewer = $this->getObject('viewer','cfe');
	$academicsContent = '<div id="Academics">'.$viewer->getStory("homepage",7).'</div>';
	$shortCoursesContent = '<div id="ShortCourses">'.$viewer->getStory("homepage",5).'</div>';
	$entrepreneursContent = '<div id="Entrepreneurs">'.$viewer->getStory("homepage",8).'</div>';
	$visionContent = '<div id="Vision">'.$viewer->getStory("homepage",6).'</div>';
	$newsLetterContent = '<div id="Newsletter">'.$viewer->getStory("homepage",4).'</div>';
	$rightColumnContent = '<div id="CfESideMenu">'.
	$viewer->getStory("homepage",1).
	'</div>'.
	'<div id="GEW">'.
	$viewer->getStory("homepage",2).
	'</div>'.
	'<div id="PartnersHeading">'.
	$viewer->getStory("homepage",0).
	'</div>'.
	'<div id="Partners">'.
	$viewer->getStory("homepage",3).
	'</div>';
	
	
        $max=41;
        //row 1

        $table->startRow('topRow');

		$table->addCell($academicsContent,'299');
		$table->addCell($shortCoursesContent,'299');
		$table->addCell($rightColumnContent ,'', '', '','' ,'rowspan="3"');

        $table->endRow();

        //row 2
        $table->startRow('middleRow');

		$table->addCell($entrepreneursContent,'299');
		$table->addCell($visionContent,'299');
		
        $table->endRow();

	//row 3

	  $table->startRow('bottomRow');

	     $table->addCell($newsLetterContent,'', '', '', '','colspan="2"');

        $table->endRow();

echo $table->show();

?>
