<?php

/**
 * RPX Identifier dbtable derived class.
 * 
 * Class to interact with the database for the RPX module.
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
 * @package   rpx
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2009 Charl van Niekerk.
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       api
 */
class dbrpxidentifiers extends dbTable 
{
    protected $objUser;
    protected $userId;

    private $id;
    private $identifier;

    /**
     * The standard class constructor.
     */
    public function init()
    {
        parent::init('tbl_rpx_identifiers');

        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();

        if ($this->userId) {
            $row = $this->getRow('userid', $this->userId);
            $this->populate($row);
        }
    }

    private function populate($row) {
        if ($row && is_array($row) && count($row)) {
            $this->id         = $row['id'];
            $this->identifier = $row['identifier'];
        }
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function put()
    {
        $row = array();
        $row['userid']     = $this->userId;
        $row['identifier'] = $this->identifier;
        if ($this->id) {
            $this->update('id', $this->id, $row);
        } else {
            $this->id = $this->insert($row);
        }
    }
}
