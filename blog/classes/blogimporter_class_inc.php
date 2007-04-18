<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class to facilitate import of existing blog content from a remote server
 * This class should allow a connection to a remote database on a remote server to get all content items within
 * that database table in order to process and import the content back into a Chisimba installation.
 *
 * Please note that due to the way that this class acts, it is only necessary to supply a username/userid to
 * the function calls in order to get an Associative array of values back to be returned.
 *
 * @author Paul Scott
 * @copyright AVOIR GNU/GPL
 * @package blog
 * @access public
 */
class blogimporter extends object
{
    /**
     * The DSN to the database to import FROM
     *
     * @var mixed Data Source Name of the data that you wish to import
     */
    public $dsn;
    /**
     * Table name of the tables that we need to connect to
     *
     * @var string
     */
    protected $_tableName;
    /**
     * The database (remote) connection object
     *
     * @var object
     */
    protected $objDb;
    /**
     * Language Object
     *
     * @var object
     */
    public $objLanguage;
    /**
     * Standard init function for the object and controller class
     *
     * @access public
     * @param void
     * @return void
     */
    public function init() 
    {
        //language object
        $this->objLanguage = $this->getObject('language', 'language');
    }
    /**
     * Pseudo constructor method. We have not yet used the standard init() function here, or extended dbTable, as we are not really
     * interested in connecting to the local db with this object.
     *
     * @param The name of the server to connect to (predefined) $server
     * @return string, set DSN
     * @access public
     */
    public function setup($server) 
    {
        switch ($server) {
            case 'localhost':
                $this->dsn = 'mysql://root:@localhost/nextgen4';
                return $this->dsn;
                break;

            case 'fsiu':
                $this->dsn = 'mysql://reader:reader@172.16.203.173/fsiu';
                return $this->dsn;
                break;

            case 'elearn':
                $this->dsn = 'mysql://reader:reader@172.16.203.210/nextgen';
                return $this->dsn;
                break;
            case 'freecourseware':
            	$this->dsn = 'mysql://next:n3xt@172.16.203.178/ocw';
            	return $this->dsn;
            	break;
            case '5ive':
            	$this->dsn = 'mysql://root:0h5h1t.pear@196.21.45.41/chisimba';
            	return $this->dsn;
            	break;
            case 'pear':
            	$this->dsn = 'mysql://root:0h5h1t.pear@196.21.45.41/chisimbademo';
            	return $this->dsn;
            	break;
            case 'dfx':
            	$this->dsn = 'mysql://root:0h5h1t.pear@196.21.45.41/dfx';
            	return $this->dsn;
            	break;
        }
    }
    /**
     * Build and instantiate the database object for the remote
     *
     * @param void
     * @return object
     * @access private
     */
    public function _dbObject() 
    {
        require_once 'MDB2.php';
        //MDB2 has a factory method, so lets use it now...
        $this->objDb = &MDB2::factory($this->dsn);
        //Check for errors on the factory method
        if (PEAR::isError($this->objDb)) {
            throw new customException($this->objLanguage->languageText("mod_blog_import_noconn", "blog"));
        }
        //set the options
        $this->objDb->setOption('portability', MDB2_PORTABILITY_FIX_CASE);
        //load the date and iterator MDB2 Modules.
        MDB2::loadFile('Date');
        MDB2::loadFile('Iterator');
        //Check for errors
        if (PEAR::isError($this->objDb)) {
            throw new customException($this->objLanguage->languageText("mod_blog_import_noconn", "blog"));
        }
        return $this->objDb;
    }
    /**
     * Method to query an arbitrarary remote table
     *
     * @param string $table
     * @param string $filter can be full SQL Query
     * @return resultset
     * @access public
     */
    public function queryTable($table, $filter) 
    {
        $this->_tableName = $table;
        $res = $this->objDb->query($filter);
        //set the return mode to return an associative array
        return $res->fetchAll(MDB2_FETCHMODE_ASSOC);
    }
    /**
     * Method to get the blog contents per user (username) into an array
     *
     * @param string $username
     * @return array
     * @access public
     */
    public function importBlog($username) 
    {
        $this->objUser = $this->getObject('user', 'security');
        //set the table
        $this->_tableName = 'tbl_users';
        //set up the query to check userid and username
        $fil1 = "SELECT * FROM tbl_users WHERE username = '$username'";
        $res1 = $this->objDb->query($fil1);
        if (PEAR::isError($res1)) {
            throw new customException($res1->getMessage());
            exit;
        }
        $ures = $res1->fetchAll(MDB2_FETCHMODE_ASSOC);
        $fname = $ures[0]['firstname']." ".$ures[0]['surname'];
        //lets check that the users name is the same, or else drop his ass
        $locname = trim($this->objUser->fullname());
        $fname = trim($fname);
        if ($fname == $locname) {
            //now get the info we need
            //set the userid as in the blog
            $userid = $ures[0]['userid'];
            //set the table to the blog table
            $this->_tableName = 'tbl_blog';
            //query the blog table
            $fil2 = "SELECT * FROM tbl_blog WHERE userid = '$userid'";
            $res2 = $this->objDb->query($fil2);
            if (PEAR::isError($res2)) {
                //uh oh.... blog not installed, or cannot be found on remote
                throw new customException($this->objLanguage->languageText("mod_blog_import_noblog", "blog"));
            }
            //return the associative array of fetched values.
            $bres = $res2->fetchAll(MDB2_FETCHMODE_ASSOC);
            if (empty($bres)) {
                throw new customException($this->objLanguage->languageText("mod_blog_import_unoblog", "blog")); //56
                
            } else {
                return $bres;
            }
        } else {
            throw new customException($this->objLanguage->languageText("mod_blog_import_unomatch", "blog"));
        }
    }
} //end class

?>