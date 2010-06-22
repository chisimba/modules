<?php
class vowfmtoolbar extends object {

    function init() {
        $this->objAltConfig = $this->getObject('altconfig','config');
    }

    function show() {
        //$siteRoot=$this->objAltConfig->getsiteRoot();
        // $skinUri=$this->objAltConfig->getskinRoot();
        //  $imgPath=$siteRoot."/".$skinUri.'/vowfm/images/spacer.gif';

        $menu='


<div class="rbroundbox">
<div class="rbtop"><div></div></div>
<div class="rbcontent">

   <div id="myslidemenu" class="jqueryslidemenu">
<ul>
<li><a href="#">Music</a></li>
<li><a href="#">DJs & Shows</a>
  <ul>
  <li class="level2"><a href="#">Show Lineup</a></li>
  <li><a href="#">DJ pages</a></li>
  <li><a href="#">DJ contact</a></li>
  
  </ul>
</li>
<li><a href="#">Competitions</a>
<ul>
  <li><a href="#">Current</a></li>
  <li><a href="#">Past</a>
  <li><a href="#">Upcoming</a>
  <li><a href="#">Rules</a>
  </ul>


</li>
<li><a href="#">Events and News</a>
  <ul>
  <li><a href="#">Upcoming Events</a></li>
  <li><a href="#">Events Guide</a>
  <li><a href="#">Submit an Event</a>
  <li><a href="#">Event Gallaries</a>
  </li>
  </ul>
</li>
<li><a href="#">Videos</a></li>
<li><a href="#">Advertise</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Jobs</a>

  <ul>
  <li><a href="#">VOW</a></li>
  <li><a href="#">Student Jobs</a>
  <li><a href="#">Services</a>
  
  </li>
  </ul>

</li>
</ul>
<br style="clear: left" />
</div>


</div><!-- /rbcontent -->
<div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->';
        return  $menu;
    }
}

?>
