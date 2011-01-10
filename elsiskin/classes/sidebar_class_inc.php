<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class sidebar extends object {
	// page main content displayed according to current action
	private $currentAction;
	
	// path to root folder of skin
	private $skinpath;
	
	/**
     * Constructor
     */
    public function init() {}

	public function setCurrentAction($action) {
		$this->currentAction = $action;
	}
	
	/**
     * Method to show the Toolbar
     * @return string $retstr containing all the toolbar links.
     */
	 
	 public function setSkinPath($skinpath) {
		 $this->skinpath = $skinpath;
	 }
	
	/**
      * Method to show the rotating images with news right below the toolbar
      * @return string
      */
	public function show() {
		switch($this->currentAction) {
			case 'about':return $this->showAboutSidebar(); 
						 break;
			case 'staff':return $this->showStaffSidebar();
						 break;
			case 'contact': return $this->showContactSidebar();
						 break;
			default: 	 return $this->showHomeSidebar();
		}
	}
	 
	 /**
      * Method to show the rotating images with news right below the toolbar
      * @return string
      */
	 public function showHomeSidebar() {
		 $retstr = '<!-- Start: Sidebar -->
                <div class="grid_1">
                    <div id="Sidebar">
                         <div id="facebook">';
						 	$fbwidgetpath = $this->getResourceUri('fb-widget.inc.php','elsiskin');
							$fh = fopen($fbwidgetpath,'r');// or die($php_errormsg);
							$fbwidget = fread($fh,filesize($fbwidgetpath));
							$retstr .= $fbwidget;
							fclose($fh); // or die($php_errormsg);
							   
							$retstr .= '</div>
                        <div id="twitter">';
                        	$twitwidgetpath = $this->getResourceUri('twi-widget.inc.php','elsiskin');
							$fh = fopen($twitwidgetpath,'r');// or die($php_errormsg);
							$twitwidget = fread($fh,filesize($twitwidgetpath));
							$retstr .= $twitwidget;
							fclose($fh); // or die($php_errormsg);
                            $retstr .= '</div>
                    </div>
                </div>
                <!-- end .grid_1 -->
                <!-- End: Sidebar -->';
	
		return $retstr;
	}
	
	public function showAboutSidebar() {
		$retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
					<div class="grid_1">
                    	<p><img src="'.$this->skinpath.'/images/dots_point.png"></p>
					</div>
				</div>
				 <!-- end .grid_1 -->
                <!-- End: Sidebar -->';
				
		return $retstr;	
	}
	
	public function showStaffSidebar() {
		$retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
					<div class="grid_1">
                    	<p><img src="'.$this->skinpath.'images/dots_who.png"></p>
					</div>
				</div>
				 <!-- end .grid_1 -->
                <!-- End: Sidebar -->';
		return $retstr;	
	}
	
	public function showContactSidebar() {
		$retstr = '<!-- Start: Sidebar -->
                <div id="Sidebar">
					<div class="grid_1">
                    	<p><img src="'.$this->skinpath.'images/dots_email.png"></p>
					</div>
				</div>
				 <!-- end .grid_1 -->
                <!-- End: Sidebar --';
		
		return $retstr;	
	}
}