<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of reportmanager_class_inc
 *
 * @author davidwaf
 */
class reportmanager extends object {

    public function init() {
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    }

    public function generatePDFReport($text, $outputname, $templateName="test.jrxml") {

        $objAltConfig = $this->getObject('altconfig', 'config');
        $modPath = $objAltConfig->getModulePath();
        $moduleUri = $objAltConfig->getModuleURI();
        $siteRoot = $objAltConfig->getSiteRoot();
        $resourcePath = $siteRoot . "/" . $moduleUri . '/unesco_oer/resources/';

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $jdbcURL = $objSysConfig->getValue('JDBC_URL', 'unesco_oer');
        $jdbcUsername = $objSysConfig->getValue('JDBC_USERNAME', 'unesco_oer');
        $jdbcPassword = $objSysConfig->getValue('JDBC_PASSWORD', 'unesco_oer');
        $port = $objSysConfig->getValue('JAVA_BRIDGE_PORT', 'unesco_oer');

        $host = $resourcePath; //$objSysConfig->getValue('JAVA_BRIDGE_HOST', 'unesco_oer');

        $this->checkJavaExtension($host, $port);

        $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");

        $report = $compileManager->compileReport("/var/www/html/unesco_oer/packages/unesco_oer/resources/reports/".$templateName);

        $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");

        $params = new Java("java.util.HashMap");
        $params->put("region", $text);
        $params->put("theme","");
        $params->put("type","");
        $params->put("institution","");
        $params->put("language","");

        $emptyDataSource = new Java("net.sf.jasperreports.engine.JREmptyDataSource");
        $jasperPrint = $fillManager->fillReport($report, $params, $emptyDataSource);

        $outputPath = realpath(".") . "/" . $outputname;

        $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
        $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);

        header("Content-type: application/pdf");
        readfile($outputPath);

        unlink($outputPath);      

    }

    function checkJavaExtension($host, $port) {

        if (!extension_loaded('java')) {
            $sapi_type = php_sapi_name();
            //$port = (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT']) > 1024)) ? $_SERVER['SERVER_PORT'] : $port;

            if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli") {
                if (!(PHP_SHLIB_SUFFIX == "so" && @dl('java.so')) && !(PHP_SHLIB_SUFFIX == "dll" && @dl('php_java.dll')) && !(@include_once("java/Java.inc")) && !(require_once("http://127.0.0.1:$port/java/Java.inc"))) {
                    echo 'test';
                    die();
                    return "java extension not installed.";
                }
            } else {

                if (!(@include_once("java/Java.inc"))) {
                    $javainc = "$host/reports/java/Java.inc";
                    require_once('packages/unesco_oer/resources/reports/java/Java.inc');
                }
            }
        }
        if (!function_exists("java_get_server_name")) {
            echo "The loaded java extension is not the PHP/Java Bridge";

        }
                           
        return true;
         
    }

}
?>
