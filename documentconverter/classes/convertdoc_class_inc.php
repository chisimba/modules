<?php

/**
* Document Converter
*
* This class is a wrapper to PyODConverter / JODConverter and uses OpenOffice as
* a service.
*
* Website: http://www.artofsolving.com/opensource
* 
* To run this class, you need:
* 1) either python or java installed
* 2) OpenOffice started as a service: soffice -headless -accept="socket,port=8100;urp;"
*
* @author Tohir Solomons
*/
class convertdoc extends object
{
    /**
    * Constructor
    */
    public function init()
    {
        //system('soffice -headless -accept="socket,port=8100;urp;"');
    }
    
    /**
    * Method to convert a document from one format to the other
    * @param string $inputFilename Absolute Path to the file
    * @param string $destination Absolute Path of the destination
    *
    * Extension of destination file determines the type of conversions
    * For the list of supported formats, see: http://www.artofsolving.com/node/17
    *
    * Destination directory exists
    */
    public function convert($inputFilename, $destination)
    {
        if (!file_exists($inputFilename)) {
            return 'inputfiledoesnotexist';
        }
        
        // At the moment, only the java version is supported
        // Todo: Make it configurable to do it via python
        return $this->javaConvert($inputFilename, $destination);
    }
    
    /**
    * Method to convert a document using Java
    *
    * @param string $inputFilename Absolute Path to the file
    * @param string $destination Absolute Path of the destination
    * @return string Conversion Result
    */
    private function javaConvert($inputFilename, $destination)
    {
        $command = 'java -jar '.$this->getResourcePath('jodconverter-2.2.0/lib/jodconverter-cli-2.2.0.jar').'  '.$inputFilename.' '.$destination;
        
        //echo $command;
        
        return system($command);
    }
    
    /**
    * Method to convert a document using Java
    *
    * @param string $inputFilename Absolute Path to the file
    * @param string $destination Absolute Path of the destination
    * @return string Conversion Result
    */
    private function pythonConvert($inputFilename, $destination)
    {
        $command = 'python '.$this->getResourcePath('pyodconverter-0.9/DocumentConverter.py').'  '.$inputFilename.' '.$destination;
        
        //echo $command;
        
        return system($command);
    }

    
}
?>