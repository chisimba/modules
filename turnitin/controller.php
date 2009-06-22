<?php
//ini_set('error_reporting', 'E_ALL & ~E_NOTICE');
/**
 * TurnItIn controller class
 *
 * Class to control the IM module
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
 * @category  chisimba
 * @package   turnitin
 * @author    Wesley Nitsckie <wesleynitsckie@gmail.com>
 * @copyright 2009 Wesley Nitsckie
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License 
 * @link      http://avoir.uwc.ac.za 
 */

class turnitin extends controller 
{
	
	/**
	 * The constructor
	 *
	 */
	public function init()
	{
		$this->objTOps = $this->getObject('turnitinops');
		
	}
	
	/**
	 * The standard dispatch funtion
	 *
	 * @param unknown_type $action
	 */
	
	public function dispatch($action)
	{
		switch ($action)
		{
			default:
				print $this->objTOps->APILogin();
				break;
			case 'callback':
				$m = var_export($_REQUEST, true);
				error_log($m);
				var_dump($_REQUEST);;
				break;
		}
	}
	
	/**
	 * Method to disable the login 
	 * feature 
	 */
	public function requiresLogin()
	{
		return FALSE;
	}
}