<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbemailtemplates extends dbTable{

    public function init()
    {
        parent::init('tbl_ads_emailtemplates');  //super
        $this->table = 'tbl_ads_emailtemplates';
        $this->objUser = $this->getObject( 'user', 'security' );

    }

    public function addTemplate($code,$content,$subject) {
        $data = array('content'=>$content,'code'=>$code,'subject'=>$subject);
        $this->insert($data);
    }
    public function updateTemplate($code,$content,$subject) {
         $data = array('content'=>$content,'subject'=>$subject);
        $this->update('code', $code, $data, $this->table);
    }
    
    public function getTemplateContent($code) {
        $data = $this->getRow('code', $code, $this->table);
        $unformattedcontent= $data['content'];
        $order   = array("\r\n", "\n", "\r");
        $replace = '<br />';
        // Processes \r\n's first so they aren't converted twice.
        return str_replace($order, $replace, $unformattedcontent);
    }

   public function getTemplateSubject($code) {
        $data = $this->getRow('code', $code, $this->table);
        return $data['subject'];
        
    }
    public function templateExists($code) {
        return $this->valueExists('code', $code, $this->table);
    }

}
?>
