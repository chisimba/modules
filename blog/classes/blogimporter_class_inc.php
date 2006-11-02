<?php

class blogimporter
{
	/**
	 * The DSN to the database to import FROM
	 *
	 * @var mixed
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
	public $objDb;

	/**
	 * Pseudo constructor method.
	 *
	 * @param The name of the server to connect to (predefined) $server
	 * @return string, set DSN
	 */
	public function setup($server)
	{
		switch ($server)
		{
			case 'localhost':
				$this->dsn = 'mysql://root:@localhost/nextgen';
				return $this->dsn;
				break;
			case 'fsiu':
				$this->dsn = 'mysql://reader:reader@172.16.203.173/fsiu';
				return $this->dsn;
				break;
			case 'elearn':
				$this->dsn = NULL;
				return $this->dsn;
				break;
		}
	}

	/**
	 * Build and instantiate the database object for the remote
	 *
	 * @return object
	 */
	public function dbObject()
	{
		require_once 'MDB2.php';
		//MDB2 has a factory method, so lets use it now...
		$this->objDb = &MDB2::factory($this->dsn);
		//Check for errors on the factory method
		if (PEAR::isError($this->objDb)) {
			return FALSE;
		}
		//set the options
		$this->objDb->setOption('portability', MDB2_PORTABILITY_FIX_CASE);
		MDB2::loadFile('Date');
		MDB2::loadFile('Iterator');
		//Check for errors
		if (PEAR::isError($this->objDb)) {
			return FALSE;
		}
		return $this->objDb;
	}

	/**
	 * Method to query an arbitrarary remote table
	 *
	 * @param string $table
	 * @param string $filter
	 * @return resultset
	 */
	public function queryTable($table, $filter)
	{
		$this->_tableName = $table;
		$res = $this->objDb->query($filter);
		return $res->fetchAll(MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Method to get the blog contents per user (username) into an array
	 *
	 * @param string $username
	 * @return array
	 */
	public function importBlog($username)
	{
		$this->_tableName = 'tbl_users';
		$fil1 = "SELECT * FROM tbl_users WHERE username = '$username'";
		$res1 = $this->objDb->query($fil1);
		$ures = $res1->fetchAll(MDB2_FETCHMODE_ASSOC);
		//now get the info we need
		$userid = $ures[0]['userid'];
		//set the table to the blog table
		$this->_tableName = 'tbl_blog';
		$fil2 = "SELECT * FROM tbl_blog WHERE userid = '$userid'";
		$res2 = $this->objDb->query($fil2);
		if(PEAR::isError($res2))
		{
			die("no blog table");
		}
		$bres = $res2->fetchAll(MDB2_FETCHMODE_ASSOC);
		return $bres;
	}

}
?>