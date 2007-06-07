<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
ini_set("max_execution_time", -1);
// end security check
class adm extends controller
{
	/**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
            	
            case 'maillog':
            	$path = $this->objConfig->getsiteRootPath()."/error_log/sqllog.log";
            	if(file_exists($path) && filesize($path) > 0)
            	{
            		echo filesize($path);
            		// bomb a mail off to the mirrors with the sql attached.
            		$objMailer = $this->getObject('email', 'mail');
					$objMailer->setValue('to', array('pscott@uwc.ac.za'));
					$objMailer->setValue('from', 'noreply@chisimba.mirr.or');
					$objMailer->setValue('fromName', $this->objLanguage->languageText("mod_adm_emailfromname", "adm"));
					$objMailer->setValue('subject', $this->objLanguage->languageText("mod_adm_emailsub", "adm"));
					$objMailer->setValue('body', date('r'));
					$objMailer->attach($path, $this->objLanguage->languageText("mod_adm_sqldata", "adm"));
					if ($objMailer->send()) {
		   				echo "Sent!";
		   				unlink($path);
		   				touch($path);
					} else {
		   				echo "Uh-oh!";
					}
            	}
            	else {
            		echo "File is of zero length";
            	}
        }
    }
}
?>