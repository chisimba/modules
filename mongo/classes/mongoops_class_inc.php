<?php

/**
 * MongoDB Helper Class
 * 
 * Convenience class for interacting with MongoDB
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
 * @package   mongo
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 * @seealso   http://www.mongodb.org/
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
 * MongoDB Helper Class
 * 
 * Convenience class for interacting with MongoDB.
 * 
 * @category  Chisimba
 * @package   mongo
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 * @seealso   http://www.mongodb.org/
 */
class mongoops extends object
{
    /**
     * The name of the collection to default to.
     *
     * @access private
     * @var    string
     */
    private $collection;

    /**
     * Cache of MongoCollection objects.
     *
     * @access private
     * @var    array
     */
    private $collectionCache;

    /**
     * The name of the database to default to.
     *
     * @access private
     * @var    string
     */
    private $database;

    /**
     * Cache of MongoDB objects.
     *
     * @access private
     * @var    array
     */
    private $databaseCache;

    /**
     * Instance of the Mongo class.
     *
     * @access private
     * @var    object
     */
    private $objMongo;

    /**
     * Instance of the dbsysconfig class of the sysconfig module.
     *
     * @access private
     * @var    object
     */
    private $objSysConfig;

    /**
     * Retrieves the MongoCollection object according to the specified collection and database names.
     *
     * @access private
     * @param  string $collection The name of the collection.
     * @param  string $database   The name of the database.
     * @return object The corresponding instance of the MongoCollection class.
     */
    private function getCollection($collection=NULL, $database=NULL)
    {
        // Use the default if the collection name has not been specified.
        if ($collection === NULL) {
            $collection = $this->collection;
        }

        // Use the default if the database name has not been specified.
        if ($database === NULL) {
            $database = $this->database;
        }

        // Ensure the Mongo connection has been initialised.
        if (!is_object($this->objMongo)) {
            $this->objMongo = new Mongo($this->objSysConfig->getValue('server', 'mongo'));
        }

        // Retrieve the MongoDB object from cache or create it.
        if (array_key_exists($database, $this->databaseCache)) {
            $objDatabase = $this->databaseCache[$database];
        } else {
            $objDatabase = $this->objMongo->$database;
            $this->databaseCache[$database] = $objDatabase;
            $this->collectionCache[$database] = array();
        }

        // Retrieve the MongoCollection object from cache or create it.
        if (array_key_exists($collection, $this->collectionCache[$database])) {
            $objCollection = $this->collectionCache[$database][$collection];
        } else {
            $objCollection = $objDatabase->$collection;
            $this->collectionCache[$database][$collection] = $objCollection;
        }

        return $objCollection;
    }

    /*
     * Initialises some of the object's properties.
     *
     * @access public
     */
    public function init()
    {
        // Objects from other classes.
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');

        // Local properties.
        $this->collectionCache = array();
        $this->database        = $this->objSysConfig->getValue('database', 'mongo');
        $this->databaseCache   = array();
    }

    /**
     * Runs a query on a collection.
     *
     * @access public
     * @param  array  $query      The query to run.
     * @param  array  $fields     The fields to return.
     * @param  string $collection The name of the collection to run the query on.
     * @param  string $database   The name of the database containing the collection.
     * @return object Instance of the MongoCursor class.
     */
    public function find(array $query=array(), array $fields=array(), $collection=NULL, $database=NULL)
    {
        if($database === NULL)
        {
            $database = $this->database;
        }
        if($collection === NULL)
        {
            $collection = $this->collection;
        }
        
        return $this->getCollection($collection, $database)->find($query, $fields);
    }

    public function removeRecord(array $record=array(), $justone = TRUE, $collection = NULL, $database = NULL)
    {
        if($database === NULL)
        {
            $database = $this->database;
        }
        
        if($collection === NULL)
        {
            $collection = $this->collection;
        }
        
        if($justone === TRUE) {
            $justone = array("justOne" => true)
        }
        else {
            array("justOne" => false)
        }
        
        return $this->getCollection($collection, $database)->remove($record, $justone);   
    }
    
    public function upsert() 
    {
        
    }
    
    /**
     * Imports a CSV file into a collection.
     *
     * @access public
     * @param  string  $file       The location of the file.
     * @param  string  $collection The collection to use.
     * @param  string  $database   The database containing the collection.
     * @return boolean The results of the import.
     */
    public function importCSV($file, $collection=NULL, $database=NULL)
    {
        $handle = fopen($file, 'r');
        $keys = array_map('strtolower', fgetcsv($handle));
        $success = TRUE;
        while (($record = fgetcsv($handle)) !== FALSE) {
            $data = array_combine($keys, $record);
            $success = $this->insert($data, $collection, $database) && $success;
        }

        fclose($handle);

        return $success;
    }

    /**
     * Inserts data into the collection.
     *
     * @access public
     * @param  array   $data       The data to insert.
     * @param  string  $collection The collection to insert the data into.
     * @param  string  $database   The database containing the collection.
     * @return boolean The results of the insert.
     */
    public function insert(array $data, $collection=NULL, $database=NULL)
    {
        if($database === NULL)
        {
            $database = $this->database;
        }
        if($collection === NULL)
        {
            $collection = $this->collection;
        }
        return $this->getCollection($collection, $database)->insert($data);
    }

    /**
     * Sets the name of the default collection.
     *
     * @access public
     * @param  string $collection The name of the default collection.
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Sets the name of the default database.
     *
     * @access public
     * @param  string $database The name of the default database.
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }
    
    /**
     * Method to list all collections in a database
     *
     * @return array or NULL
     */
    public function listCollections()
    {
        // Ensure the Mongo connection has been initialised.
        if (!is_object($this->objMongo)) {
            $this->objMongo = new Mongo($this->objSysConfig->getValue('server', 'mongo'));
        }
        $db = $this->objMongo->selectDB($this->database);
        $list = $db->listCollections();
        foreach($list as $coll) {
            $l[] = $coll->__toString();
        }
        return $l;
    }
}

?>
