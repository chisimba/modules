<?php

/**
 * Facade class to the pheanstalk library.
 * 
 * Library methods for interacting with the beanstalk queue.
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
 * @package   beanstalk
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
 * Facade class to the beanstalk library.
 * 
 * Library methods for interacting with the beanstalk queue.
 * 
 * @category  Chisimba
 * @package   beanstalk
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 */
class beanstalkops extends object
{
    /**
     * Instance of the Pheanstalk class.
     *
     * @access private
     * @var    object
     */
    private $objPheanstalk;

    /**
     * Instance of the dbsysconfig class of the sysconfig module.
     *
     * @access private
     * @var    object
     */
    private $objSysConfig;

    /**
     * Initialises the object properties.
     *
     * @access public
     */
    public function init()
    {
        // Retrieve the configuration variables.
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $host = $this->objSysConfig->getValue('host', 'beanstalk');

        // Load the pheanstalk library.
        include $this->getResourcePath('pheanstalk_init.php');
        $this->objPheanstalk = new Pheanstalk($host);
    }

    /**
     * Adds a task to the specified queue.
     *
     * @access public
     * @param  string  $queue The name of the queue.
     * @param  string  $task  The value of the task.
     * @return integer The number of the new task.
     */
    public function add($queue, $task)
    {
        $this->objPheanstalk->useTube($queue)->put($task);
    }

    /**
     * Binds a method in an object to a queue and waits for incoming tasks.
     *
     * @access public
     * @param  string $queue  The name of the queue.
     * @param  object $object The object containing the callback method.
     * @param  string $method The name of the callback method.
     */
    public function listen($queue, object $object, $method)
    {
        while (true) {
            $job = $this->objPheanstalk->watch($queue)->ignore('default')->reserve();
            try {
                $object->$method($job->getData());
                $this->objPheanstalk->delete($job);
            } catch (Exception $e) {
            }
         }
    }
}
