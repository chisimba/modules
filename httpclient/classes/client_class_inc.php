<?php
require_once('httpclients_class_inc.php');
/**
 * Adaptor class to facilitate the HTTP Client
 *
 * @access public
 * @author Paul Scott
 * @filesource
 */

class client extends object
{
	/**
	 * Standard init function
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function init()
	{
		try {
        	$this->objFilters = $this->getObject('filter','filters');
        	$this->objResponse = $this->getObject('httpresponse');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			die();
		}
	}

	/**
	 * Method to get a specified url and return it to the calling class
	 *
	 * @param mixed $url
	 * @return string
	 */
	public function getURL($url)
	{
		$http = new httpclients($url);
        $response = $http->get();
        if ($response->isSuccessful()) {
        		return $response->getBody();
        } else {
        		return FALSE;
        }
    }
}
?>