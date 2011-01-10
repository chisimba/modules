<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class elsifooter extends object {
	
	/**
     * Constructor
     */
    public function init() {}
	
	public function show() {
		$retstr = '
			<!-- Start: Footer -->
			<div id="Footer">
                <div class="grid_4">&nbsp;</div>
                <!-- end .grid_4 -->
                <div class="clear">&nbsp;</div>
                <div class="grid_4">
                    <a class="current" href="index.html">Home</a> | <a href="about/index.html">About Us</a> | <a href="staff/index.html">Staff A - Z</a> | <a href="news/index.html">News</a>|<a href="research/index.html">Research</a>|<a href="support/index.html">Support</a>|<a href="projects/index.html">Projects</a></div>
            </div>';
		$retstr .= '<div class="clear">&nbsp;</div>';
			
		return $retstr;
	}
}