<?php
$info=$this->objAddAd->getBanners();

$str = "<table cellpadding=4>
	<tr>
	<th>Title</th><th>Description</th><th>Banner size</th><th>Date Created</th><th>Date Updated</th><th>Is Active</th><th colspan=5>Action</th>
	</tr>
	";
foreach ($info as $data)
{
	$ti = $data['banner_title'];
	$de = $data['comment_desc'];
	$dc = $data['date_created'];
	$wi = $data['banner_width'];
	$he = $data['banner_height'];
	$si = $wi.'x'.$he;
	$du = $data['date_updated'];
	$id = $data['id'];
	$act = $data['is_active'];
	if ($act == 0) {
		$active = "NO";
	}
	else {
		$active = "YES";
	}

	
	$objViewIcon = $this->newObject('geticon', 'htmlelements');
	$objViewIcon->title="View Banner";
	$viewLink=$this->uri(array('action'=>'viewbanner','id'=>''.$id.''),"adbanner");
	$viewBanner = $objViewIcon->getViewIcon($viewLink, '');

	$objEditInfoIcon = $this->newObject('geticon', 'htmlelements');
	$objEditInfoIcon->title="Edit Banner Info";
	$editInfoLink=$this->uri(array('action'=>'editbannerinfo','id'=>''.$id.''));
	$editInfo = $objEditInfoIcon->getLinkedIcon($editInfoLink, 'editmetadata');

	$objEditImgsIcon = $this->newObject('geticon', 'htmlelements');
	$objEditImgsIcon->title="Edit Banner Image";
	$editImgsLink=$this->uri(array('action'=>'editbannerimage','id'=>''.$id.''),"adbanner");
	$editImgs = $objEditImgsIcon->getLinkedIcon($editImgsLink, 'edit');

	// The delete icon with link uses confirm delete utility
	$objDelIcon = $this->newObject('geticon', 'htmlelements');
	$objDelIcon->title="Delete Banner";
	$objDelIcon->setIcon("delete");
	$objConfirm =  $this->newObject('confirm','utilities');
	$delText = "Are you sure you want to Delete this Banner. Continue?";
	$deleteBannerLink=$this->uri(array('action'=>'deletebanner','id'=>''.$id.''),"adbanner");
	$objConfirm->setConfirm($objDelIcon->show(),$deleteBannerLink,$delText);
    $deleteBanner = $objConfirm->show();

	
	$str = $str."<tr><td>".$ti."</td><td>".$de."</td><td>".$si."</td><td>".$dc."</td><td>".$du."</td><td>".$active."</td><td>".$viewBanner."</td><td>".$editInfo."</td><td>".$editImgs."</td><td>".$deleteBanner."</td></tr>";
}
$str = $str."</table>";

echo $str;
?>
