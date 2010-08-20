<?php
class textblock_installscripts extends dbTable {
    public function init() {
        parent::init('tbl_textblock');
		
		$this->objModuleBlocks = $this->getObject('dbblocksdata', 'blocks');
		$this->objModules = $this->getObject('modules', 'modulecatalogue');
    }

    public function preinstall($version = NULL) {
		switch ($version) {
			
			case '0.858':
				log_debug('executing preinstall code for textblock module version: 0.858');
				if ($this->objModules->checkIfRegistered('cms')) {
					$count = 1;
					$objCMSBlocks = $this->getObject('dbblocks', 'cmsadmin');
					$textBlocks = $this->getAll();
					foreach ($textBlocks as $tBlock) {
						$moduleBlock = $this->objModuleBlocks->getRow('blockname', $tBlock['blockid']);
						if ($moduleBlock) {
							$cmsBlocks = $objCMSBlocks->getAll("WHERE blockid = '{$moduleBlock['id']}'");
							foreach ($cmsBlocks as $cmsBlock) {
								if ($cmsBlock['blockid'] != $tBlock['id']) {
									log_debug("TEXTBLOCK: Changed block $count: {$tBlock['blockid']}");
									$count++;
								}
								$cmsBlock['blockid'] = $tBlock['id'];
								$objCMSBlocks->update('id', $cmsBlock['id'], $cmsBlock);
							}		
						}
					}
					$this->objModuleBlocks->delete('moduleid', 'textblock');
				}
				break;
			
			default:
				log_debug('no postinstall for this update');
				break;
		}
        return 'postinstall done';
    }
	
	public function postinstall($version = NULL) {
		return 'postinstall done';
    }
}
?>