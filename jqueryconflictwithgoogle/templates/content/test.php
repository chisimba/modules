
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type=""  src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>


<?php

echo $str;

?>
<div id="dialog" title="Basic dialog">
	<p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>

     <script type="text/javascript">


            jQuery(document).ready(function() {
               ///Spit a Dialog Box
               jQuery("#dialog").dialog();
               

	});

                    </script>