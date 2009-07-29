<?php
$action = $this->getParam('action');
if($action == 'edit') {
    $linknum = $this->getParam('linknumber');
    $qry = "SELECT * FROM tbl_gifttable WHERE donor LIKE '%$donor' OR recipient LIKE '%$recipient' OR giftname LIKE '%$giftname'";
    $data = $this->objDbGift->getInfo($qry);
	$data = $data[$linknum];
    $form = $this->objGift->displayForm($this->objUser->fullName(),$data);
}
else {
    $form = $this->objGift->displayForm($this->objUser->fullName(),array());
}

echo $form;
?>
