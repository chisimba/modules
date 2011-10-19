<?php

/**
 * see if the java extension was loaded.
 */
function checkJavaExtension()
{
    if(!extension_loaded('java'))
    {
        $sapi_type = php_sapi_name();
        $port = (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT'])>1024)) ? $_SERVER['SERVER_PORT'] : '8082';
        if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli") 
        {
            if(!(PHP_SHLIB_SUFFIX=="so" && @dl('java.so'))&&!(PHP_SHLIB_SUFFIX=="dll" && @dl('php_java.dll'))&&!(@include_once("java/Java.inc"))&&!(require_once("http://127.0.0.1:$port/java/Java.inc"))) 
            {
                return "java extension not installed.";
            }
        } 
        else
        {
            if(!(@include_once("java/Java.inc")))
            {
                require_once("http://127.0.0.1:$port/java/Java.inc");
            }
        }
    }
    if(!function_exists("java_get_server_name")) 
    {
        return "The loaded java extension is not the PHP/Java Bridge";
    }

    return true;
}

echo "Test";
checkJavaExtension();
echo "Test 1";

$compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
$report = $compileManager->compileReport(realpath("test report1.jrxml"));

$fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");

$params = new Java("java.util.HashMap");
$params->put("text", "This is a test string by Anton Dreyer");
$params->put("number", 3.00);

$class = new JavaClass("java.lang.Class");
$class->forName("com.mysql.jdbc.Driver");
$driverManager = new JavaClass("java.sql.DriverManager");
$conn = $driverManager->getConnection("jdbc:mysql://localhost/unesco_oer","root","root");

$jasperPrint = $fillManager->fillReport($report, $params, $conn);

$outputPath = realpath(".")."/"."output.pdf";

$exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
$exportManager->exportReportToPdfFile($jasperPrint, $outputPath);

header("Content-type: application/pdf");
readfile($outputPath);

unlink($outputPath);
?>
