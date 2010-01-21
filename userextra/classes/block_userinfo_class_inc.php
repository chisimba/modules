<?php

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class block_userinfo extends object {
    function init() {
        $this->objLanguage=$this->getObject('language','language');
        $this->title=$this->objLanguage->languageText('mod_userextra_title');
        $this->objUser=$this->getObject('user','security');
        $this->objGroups = $this->getObject('groupadminmodel','groupadmin');
        $this->objOps = $this->getObject ( 'groupops','groupadmin');
        $this->dbextra=$this->getObject('dbuserextra');
    }

    function show() {
        $username='364586';//$this->objUser->username();
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $staffurl=$objSysConfig->getValue('STAFFURL', 'userextra');
        $studenturl=$objSysConfig->getValue('STUDENTURL', 'userextra');
        $displaymessage=$objSysConfig->getValue('WELCOME_MESSAGE','userextra');
        $staffurl.="/$username";
        $studenturl.="/$username";

        //first test to see if user is staff

        if(!$this->getSession('academicstatus')) {
            $ch=curl_init($staffurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $r=curl_exec($ch);
            curl_close($ch);
            $jsonArray=json_decode($r);
            $employeeCategory= $jsonArray->objects[0]->employeeCategory;
            if($employeeCategory == 'ACA') {
                $groupid=$this->objGroups->getId('Lecturers');
                $userid=$this->objUser->userid();
                $puid=$this->dbextra->getUserPuid($userid);
                $res=$this->objGroups->addGroupUser($groupid, $puid);
                $this->setSession("academicstatus","true");
            }
        }
        //test if student
        if(!$this->getSession('studentstatus')) {
            $ch=curl_init($studenturl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $r=curl_exec($ch);
            curl_close($ch);
            $jsonArray=json_decode($r);
            $studentNumber= $jsonArray->objects[0]->studentNumber;
            if($studentNumber){
                $groupid=$this->objGroups->getId('Students');
                $userid=$this->objUser->userid();
                $puid=$this->dbextra->getUserPuid($userid);
                $res=$this->objGroups->addGroupUser($groupid, $puid);
                $this->setSession("studentstatus","true");
            }
        }
        return  $displaymessage;
    }

    function determinIfAcademic() {

    }
}
?>
