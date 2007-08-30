<?php

/**
 * default_tpl.php
 *
 * @version $Id$
 * @copyright (c) 2007 Avoir
 */

$uriEnabled = $this->uri(array('action'=>'enabled'));
$uriNotEnabled = $this->uri(array('action'=>'notenabled'));

?>
<script type="text/javascript">
// <![CDATA[
if ( navigator.javaEnabled() ) {
    window.location="<?= $uriEnabled ?>";
}
else {
    window.location="<?= $uriNotEnabled ?>";
}
// ]]>
</script>