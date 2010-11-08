<?php

class dbrtt extends dbtable {

    function init() {
        parent::init('tbl_stories');
    }

    function getDemoContent() {
        $objTrim = $this->getObject('trimstr', 'strings');
        $storyid = $this->objDbSysconfig->getValue('DEFAULT_STORY_ID', 'rtt');
        $data = $this->getStory($storyid);
        $content = '';

        $content = '
          
            
            ' . $this->objWashout->parseText($data['maintext']) . '
            
            <br/>
              ';

        return $content;
    }

      function getDownloadsStory() {
        $objTrim = $this->getObject('trimstr', 'strings');
        $storyid = $this->objDbSysconfig->getValue('DEFAULT_STORY_ID', 'rtt');
        $data = $this->getStory($storyid);
        $content = '';

        $content = '


            ' . $this->objWashout->parseText($data['maintext']) . '

            <br/>
              ';

        return $content;
    }

    public function getStory($id) {
        $data = $this->getRow('id', $id);
        return $data;
    }

}

?>
