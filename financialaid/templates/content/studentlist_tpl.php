<?php
//require_once('columns_tpl.php');
$this->objLanguage = &$this->getObject('language','language');

$right =& $this->getObject('blocksearchbox','studentenquiry');
$this->objUser =& $this->getObject('user','security');
$right = $right->show($this->getParam('module','studentenquiry'));

$centersearch =& $this->getObject('blockcentersearchbox','studentenquiry');
$this->objUser =& $this->getObject('user','security');
$centersearch = $centersearch->show($this->getParam('module','studentenquiry'));
//$left =& $this->getObject('blockleftcolumn');
//$left = $left->show();

$left =& $this->getObject('financialaidleftblock');
$left = $left->show();

$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');

//echo "$numrecords --- $allrecords";
$content = "";
$oddEven = 'odd';
$foundStudents = false;

if(is_array($stdinfo)){
        $cnt = count($stdinfo);
        //***start of pages***
                //output code to var, then strip off last "|" separater
                $ncnt = $cnt/25;
                $showlinks =& $this->getObject('htmlHeading','htmlelements');
                $ncnt = strtok(($ncnt+1), ".");
                $links_code = "";
                $ncnt = $cnt/25; //assumes $total_rows is a var
                //get exact number of pages, divide by 30 and get remainder
                if ($ncnt != floor($ncnt)) {
                //sum has a remainder, so extra page needed for last rows
                $ncnt = strtok(($ncnt+1), ".");
                }
                $links_code = "";
                $viewpages = new link();
                for ($n=0; $n<$ncnt; $n++) {
                $cntr = ($n * 25) + 1;
                $num = $n + 1;
                $viewpages->href=$this->uri(array('action'=>'search','surname'=>$stdinfo[0]->SURNAM,'start_at'=>$cntr,'pg'=>$num));
                $viewpages->link = "$num";
                $links_code .= $viewpages->show();
                if ($num==$ncnt)
                  $links_code .= " ";
                else if($n < $ncnt)
                  $links_code .= " | ";

                }
                $startl = $this->getParam('start_at');
                $endl = $startl + 25;
                 $viewp ="";
                 $viewn ="";

                if ($startl > 1)
                {   $page = $this->getParam('pg');
                    $page = $page - 1;
                    $cntr = $startl - 25;
                    $viewpre = new link();
                    $viewpre->href=$this->uri(array('action'=>'search','surname'=>$stdinfo[0]->SURNAM,'start_at'=>$cntr,'pg'=>$page));
                    $viewpre->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
                    $viewp = $viewpre->show();
                }
                $vntest = $cnt - 25;
                if ($startl <= $vntest)
                {   $page = $this->getParam('pg');
                    $page = $page + 1;
                    $cntr = $startl + 25;
                    $viewnext = new link();
                    $viewnext->href=$this->uri(array('action'=>'search','surname'=>$stdinfo[0]->SURNAM,'start_at'=>$cntr,'pg'=>$page));
                    $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
                    $viewn = $viewnext->show();
                }
                $showlinks->str = $objLanguage->languagetext('mod_financialaid_respage','financialaid')."<br /><br />$viewp $links_code $viewn";
                $showlinks->align="center";
                $pagelinks = $showlinks->show();
                $Rectbl =& $this->getObject('htmlTable','htmlelements');
                $Rectbl->startRow();
              if($this->getParam('surname'))
              {
                if($endl==25)  {
                    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid')."</b>"); $Rectbl->endRow();
                    $Rectbl->startRow(); $Rectbl->addCell("<b>1</b>");
                    $Rectbl->endRow();   $endl = $endl - 1;
                    $Rectbl->startRow(); $Rectbl->addCell("<br />"); $Rectbl->endRow();
                    $Rectbl->startRow();
                    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid')."</b>"); $Rectbl->endRow();
                    $Rectbl->startRow();  $Rectbl->addCell("<b>0  to $endl</b>");
                    $Rectbl->endRow();
                   $stdinfo = $this->studentinfo->listsurn(0);
                }
                else {
                   $page = $this->getParam('pg');
                   $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid')."</b>"); $Rectbl->endRow();
                   $Rectbl->startRow(); $Rectbl->addCell("<b>$page</b>");
                   $Rectbl->endRow();
                   $Rectbl->startRow(); $Rectbl->addCell("<br />"); $Rectbl->endRow();
                   $Rectbl->startRow();
                   $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid')."</b>"); $Rectbl->endRow();  $Rectbl->startRow();
                   if($endl < $cnt){  $Rectbl->addCell("<b>$startl to $endl</b>");  }
                   else {  $Rectbl->addCell("<b>$startl to $cnt</b>");  }
                   $Rectbl->endRow();
                   $stdinfo = $this->studentinfo->listsurn($startl);
                }
              }
                $Rectbl->startRow(); $Rectbl->addCell("<br />"); $Rectbl->endRow();
                $Rectbl->startRow();
                $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid')."</b>");  $Rectbl->endRow();
                $Rectbl->startRow(); $Rectbl->addCell("<b>$cnt</b>");
                $Rectbl->endRow();
                $records = $Rectbl->show();
                //***end of pagination***

        $table =& $this->getObject('htmltable','htmlelements');

	$table->width = '100%';
	$table->cellpadding = 5;
	$table->cellspacing = 2;

	$table->startHeaderRow();
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
	//$table->addHeaderCell('Student Number');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));

	$table->endHeaderRow();

//echo "<br />student info: "; print_r($stdinfo); echo "<br />";


	if(is_array($stdinfo)){
      // echo "count: " . count($stdinfo);
         for($i = 0; $i < count($stdinfo); $i++)
         {
  		   //foreach($stdinfo as $data){
			$table->row_attributes = " class = \"$oddEven\"";

			$link = new link();

			$link->href=$this->uri(array('action'=>'info','id'=>$stdinfo[$i]->STDNUM));
			$link->link = $stdinfo[$i]->FSTNAM;

			$viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'info','id'=>$stdinfo[$i]->STDNUM));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

			$results = new link();
			$results->href=$this->uri(array('action'=>'results','id'=>$stdinfo[$i]->STDNUM));
			$results->link = "Course Information";

			$payment = new link();
			$payment->href = $this->uri(array('action'=>'payment','id'=>$stdinfo[$i]->STDNUM));
			$payment->link = "Payment Information";

            $stname = $stdinfo[$i]->FSTNAM;
            $stsname = $stdinfo[$i]->SURNAM;
            $stid = $stdinfo[$i]->IDN;
            $title = $stdinfo[$i]->TTL;
            $stnum = $stdinfo[$i]->STDNUM;
           // echo "<br />stnum: ".$stnum;
			$address = $this->studentinfo->studentAddress($stnum,35);

			$corre = new link();
			$corre->href=$this->uri(array('moduleTo'=>$this->getParam('module'),'action'=>'new',
            'moduleAction'=>'ok', 'userToName'=>$stname,'userToTitle'=>$title,'studentNo'=>$stnum,
            'fromUserId'=>$this->objUser->userId(),'detail'=>$address[0]->EMLADD,
            'type'=>'email'),'correspondence');

			//$corre->href="index.php?module=correspondence&action=new&extToSubject=testing&extType=email&extUserToTitle=toSimangMang";
			$corre->link = "Correspondence";

			$enquiry = new link();
			$enquiry->href=$this->uri(array('action'=>'enquiry','id'=>$stid));
			$enquiry->link='Enquiry';

			$table->startRow();
			$table->addCell($stname);
			$table->addCell($stsname);
			//$table->addCell($data->studentNumber);
			$table->addCell($stid);
			$table->addCell($viewdetails->show());

			$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';

		}
		$foundStudents = true;

	}
$content = $table->show();
}

if ($ncnt <= 1)
{
        $pagelinks = "";
        $records = "";
}
if ($foundStudents == false) {

	$right = '';
	$content = "<br /><br /><br />" . $centersearch;
        $pagelinks = '';
        $records = '';
}

$content = "<center>".$pagelinks." ".$content . "</center>";
$left = $left."<br />".$records;
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();


?>
