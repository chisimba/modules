<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check
require_once($this->getResourcePath('fpdf.php', 'pdfmaker'));
class fpdfwrapper extends object
{
	public $pdf;

	public function init()
	{
		define('FPDF_FONTPATH',$this->getResourcePath('font/'));
		$this->pdf = new FPDF();
	}

	public function simplePdf($text)
	{
		$this->pdf->AddPage();
		/*//parse the text and look for images
		preg_match_all('/\[img\](.*)\[\/img\]/U', $text, $matches, PREG_PATTERN_ORDER);
        unset($matches[0]);
        //print_r($matches);
        $mcount = 0;
        foreach($matches as $match)
        {
        	$text = preg_replace('/\[img\](.*)\[\/img\]/U', $this->pdf->image($match[$mcount], 0, 0, 0), $text);
        	$mcount++;
        }
       // echo $text; die();
*/
		$this->pdf->SetFont('Times','',12);
		$this->pdf->MultiCell(0,5,$text);
		//Line break
		$this->pdf->Ln();
		$this->pdf->Output();
	}

	//Simple table
	function basicTable($header,$data)
	{
		$this->pdf->SetFont('Arial','',14);
		$this->pdf->AddPage();

		//Column widths
    	$w = array(40,35,40,45);
    	//Header
    	for($i=0;$i<count($header);$i++)
        	$this->pdf->Cell($w[$i],7,$header[$i],1,0,'C');
    	$this->pdf->Ln();

    	 //Data
   		 foreach($data as $row)
   		 {
        	$this->pdf->Cell($w[0],6,$row[0],'LR');
        	$this->pdf->Cell($w[1],6,$row[1],'LR');
        	$this->pdf->Cell($w[2],6,$row[2],'LR',0,'R');
        	$this->pdf->Cell($w[3],6,$row[3],'LR',0,'R');
        	$this->pdf->Ln();
    	}
    	//Closure line
    	$this->pdf->Cell(array_sum($w),0,'','T');

    	$this->pdf->Output();
	}

}
?>
