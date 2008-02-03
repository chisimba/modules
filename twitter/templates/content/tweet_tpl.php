<?php
$url = $this->uri(array(
    "action" => "sendtweet"), "twitter");
?>
<form action="<?php echo $url; ?>" method="post">
<textarea name="tweet" id="tweeter" cols="80" rows="10">
Enter your text here!
</textarea><br />
<input type="submit" value="Tweet" />
</form>