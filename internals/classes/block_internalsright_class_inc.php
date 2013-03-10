<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of block_internalsright_class_inc
 *
 * @author monwabisi
 */
class block_internalsright extends Object {

        //put your code here

        var $objLanguage;
        var $objUser;
        var $objAltConfig;
        var $objDBleaves;

        /**
         * 
         */
        public function init() {
                $this->objLanguage = $this->getObject('language', 'language');
                $this->objUser = $this->getObject('user', 'security');
                $this->objAltConfig = $this->getObject('altconfig', 'config');
                $this->title = $this->objLanguage->languageText('word_internals_title','system');
        }
        
        public function buildList(){
                $objLink = $this->getObject('link','htmlelements');
                $list = $this->objDBleaves->getLeaveList();
                //if list is not empty, display all applicable leaves
                if(count($list) > 0){
                        foreach ($list as $value){
                                
                        }
                }
        }


        public function show(){
                
        }

}

?>
