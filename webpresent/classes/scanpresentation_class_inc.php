<?php




$this->loadClass('folderbot', 'files');


class scanpresentation extends folderbot 
{

    /**
    * @var array $fileIndex Array holding all matched files
    */
    protected $fileIndex;
    
    /**
    * @var array $folderIndex Array holding all matched folders
    */
    protected $folderIndex;
    
    /**
    * Method to scan a directory
    * @param  string $directory Directory to Scan
    * @return array  An array containing list of files and folders
    */
    public function scanDirectory($directory)
    {
        $this->set_recurse(true); // set to false to only list the folder without subfolder.
        $this->scan($directory);
        
        return $this->fileIndex;
    }

    /**
    * Method to trigger a file event and add the file to the output
    * array.  Triggered by the parent class, it is called every 
    * time a file has been found.
    */
    public function file_event()
    {
        if (preg_match('%.*/text\d*\.html%', $this->curfile)) {
            $this->fileIndex[basename($this->curfile)] = $this->curfile;
        }
    } 
    
    /**
    * Method to trigger a folder event and add thefolder to the output
    * array.  Triggered by the parent class, it is called every 
    * time a folder has been found.
    */
    public function folder_event()
    {           
        //$this->folderIndex[] = $this->curfile;
    }
    
} // end class
?>