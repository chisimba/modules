<?php
?>
    <i>Instant Messaging</i>
<?php
    // Launch an IM window for each new message received.
	$index = 0;
	foreach ($entries as $entry) 
    {
		$uri = $this->uri(array('action'=>'showMessage','messageId'=>$entry['id']), 'instantmessaging');
        $uri = str_replace('&amp;', '&', $uri);
        ?><script language="JavaScript" type="text/javascript">
		window.open(<?php
		echo "\"" . $uri. "\"";
		?>,<?php 
		echo "\"IM" . date("YmdHis") . $index . "\""; 
		?>,"width=350, height=150, scrollbars=1");
		</script>
		<?php
		$index++;
	}
?>