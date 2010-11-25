<?php

require 'php-excel.class.php';

class excelgenerator extends object {

    public function init() {
        $this->objDbGift = $this->getObject("dbgift");
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objDepartments = $this->getObject("dbdepartments");
    }

    function generateExel($departmentid, $departmentname) {
        $gifts = $this->objDbGift->getGifts($departmentid);

        $departmentid = $this->getSession("departmentid");
        $departmentname = $this->objDepartments->getDepartmentName($departmentid);

        $data = array(
            1 => array('Gift_Name,Type,Description, Donor,Value_(ZAR), Recipient, Date_Recieved, Date_Recorded')
        );
        if (count($gifts) > 0) {
            foreach ($gifts as $gift) {
                $data[] = array($gift['giftname'], $gift['gift_type'], $gift['donor'], $gift['value'], $gift['recipient'], $gift['date_recieved'], $gift['tran_date']);
            }
        }
        $filename = "giftexport";
        $xls = new Excel_XML('UTF-8', false, 'Gifts');
        $xls->addArray($data);
        $xls->generateXML($filename);
    }

}

?>
