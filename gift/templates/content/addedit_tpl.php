<?php
$action = $this->getParam('action');
if($action == 'edit') {
    $id  = $this->getParam('id');
    $qry = "SELECT * FROM tbl_gifttable WHERE id = '$id'";
    $data = $this->objDbGift->getInfo($qry);
    $data = $data[0];
    $form = $this->objGift->displayForm($this->objUser->fullName(),$data);
}
else {
    $form = $this->objGift->displayForm($this->objUser->fullName(),array());
}

echo $form;
?>
