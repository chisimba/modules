<?php
/* 
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
 */


$objDbUserGroups = $this->getObject('dbusergroups', 'unesco_oer');
$arrayUserGroups = $objDbUserGroups->getGroupsByUserID($this->objUser->userId());
foreach ($arrayUserGroups as $userGroup) {
    $uri = $this->uri(array('action'=>'selectAdaptation', 'groupid'=>$userGroup['groupid'],'originalpair'=>$this->getParam('originalpair'), 'originalproductid'=>$this->getParam('originalproductid')));
    echo $this->objGroupUtil->topcontent($userGroup['groupid'], $uri);
}


?>
