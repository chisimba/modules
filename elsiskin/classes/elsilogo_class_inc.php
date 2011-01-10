<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class elsilogo extends object {
	
	// path to root folder of skin
	private $skinpath;


    /**
     * Constructor
     */
    public function init() {}

	/**
     * Method to show the Toolbar
     * @return string $retstr containing all the toolbar links.
     */
	 
	 public function setSkinPath($skinpath) {
		 $this->skinpath = $skinpath;
	 }
	 
	 public function show() {
		 $retstr = '<div class="clear">&nbsp;</div>
            <div class="grid_3">

                <img src="' . $this->skinpath . 'images/logo_wits.png">
            </div>
    		<!-- end .grid_3 -->
            <div class="grid_1">
                <img src="' . $this->skinpath . 'images/logo_elsi.gif">
            </div>';
			
		$retstr .= '<!-- Start: Horizontal nav -->
			 <div class="clear">&nbsp;</div> 
				<div id="Horizontalnav"> 
					<div class="wide">
					</div>
			<!-- end .grid_2 .push_2 -->
				</div> 
			<!-- end .grid_1 -->
			<!-- End: header -->
			 </div>
			 <!-- End: Horizontal nav -->';
			
		return $retstr;
	 }
	 
}