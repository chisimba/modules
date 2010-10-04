<?php

class fusiontables extends object
{
    public function init()
    {
        $this->getObject('zend', 'zend');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $email = $this->objSysConfig->getValue('email', 'fusiontables');
        $password = $this->objSysConfig->getValue('password', 'fusiontables');
        $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password, 'fusiontables');
        $this->gdata = new Zend_Gdata($client);
    }

    public function query($sql)
    {
        $uri = 'http://tables.googlelabs.com/api/query?sql='.urlencode($sql);
        $csv = $this->gdata->performHttpRequest('GET', $uri)->getBody();

        $file = tmpfile();
        fwrite($file, $csv);
        fseek($file, 0);

        $headers = fgetcsv($file);
        $data = array();

        while (($row = fgetcsv($file)) !== FALSE) {
            $data[] = array_combine($headers, $row);
        }

        fclose($file);

        return $data;
    }
}
