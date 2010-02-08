<?php
	class adbannermenu extends object {		public function init() {
			$this->loadClass("link","htmlelements");
       	}

		public function getMenu() {
			$checker=$this->getObject("modules","modulecatalogue");
			
			$addBannerLink=new link($this->uri(array('action'=>'addbanner'),"adbanner"));
			$addBannerLink->link="Add Banner";
			$viewBannerLink=new link($this->uri(array('action'=>'viewbanners'),"adbanner"));
            $viewBannerLink->link="View Banners";
			
			$menu_str="<OL align='left' type='I'>
				     <li>".$addBannerLink->show()."
				     <li>".$viewBannerLink->show()."
				    
				   </OL>";
			return $menu_str; 
        }	}
?>
