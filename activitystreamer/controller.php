<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check
class activitystreamer extends controller
{
	
	public function init()
	{
		try {
			
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
	}
	
	 /**
     *
     * The standard dispatch method for
     *
     */
    public function dispatch($action)
    {
        /*
         * Convert the action into a method (alternative to
         * using case selections)
         */
        $method = $this->getMethod ( $action );
        /*
         * Return the template determined by the method resulting
         * from action
         */
        return $this->$method ();       
    }
    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     */
    protected function getMethod(& $action) {
        if ($this->validAction ( $action )) {
            return '__' . $action;
        } else {
            return '__home';
        }
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    protected function validAction($action) {
        if (method_exists ( $this, '__' . $action )) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
     /**
     * Method to list all he context
     *
     * @access protected
     */
    protected function __jsonlistactivities()
    {
    	$objUtils = $this->getObject('activityutilities','activitystreamer');
    	echo $objUtils->jsonListActivity($this->getParam('start'), $this->getParam('limit'));
    	exit(0);
    }
     /**
     * Method to list all the context chapters and pages
     *
     * @access protected
     */
    protected function __jsoncourseactivities()
    {
    	$objUtils = $this->getObject('activityutilities','activitystreamer');
    	echo $objUtils->jsonCourseActivies($this->getParam('start'), $this->getParam('limit'));
    	exit(0);
    }
     /**
     * Method to list all the context chapters and pages
     *
     * @access protected
     */
    public function __showactivities()
    {
    	 $objUtils = $this->getObject('activityutilities','activitystreamer');
    	 $objActivities = $this->getObject('block_browseactivities','activitystreamer');
      $objBlocks = $this->getObject('blocks','blocks');
      $this->setVar('pageSuppressToolbar', TRUE);
      $this->setVar('pageSuppressBanner', TRUE);
      $this->setVar('pageSuppressSearch', TRUE);
      $this->setVar('suppressFooter', TRUE);

    	 return 'activities_tpl.php';
    }

}
