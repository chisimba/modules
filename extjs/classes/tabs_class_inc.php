<?php
/**
 * This file contains the button class which is used to generate
 * HTML button elements for forms
 *
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

class tabs extends object
{
	/**
	 * Description for $callno
	 * @callno holds the numbers of time that the getResources is called
	 * @name   $kewl_entry_point_run
	 */
	private $callno = 0;


    public function init()
    {
        
    }
	 
    public function getExtjsResource()
    {
		if($callno < 1)
		{
			$extbase_js = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','extjs').'" type="text/javascript"></script>';
		
			$extall_js = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js', 'extjs').'" type="text/javascript"></script>';
		
			$extall_css = '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css', 'extjs').'" type="text/css" />';
		
			$this->appendArrayVar('headerParams', $extbase_js);
			$this->appendArrayVar('headerParams', $extall_js);
			$this->appendArrayVar('headerParams', $extall_css);
		//error_log(var_export($callno, true));
		}
		$callno++;
		//error_log(var_export($callno, true));
			//return $extbase_js.$extall_js.$extall_css;
			
	}   

}
?>
