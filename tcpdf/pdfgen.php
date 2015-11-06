<?php

require_once('config/lang/eng.php');
require_once('tcpdf.php');

$book_id = $_POST["book"];

$con = mysqli_connect("di-drupal.d-infinity.net","di_drupal","Dru52e5w", "di_drupal");
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

//$book_id = mysqli_real_escape_string($con, $_GET["bid"]);////////////////////////////////////

if($result = mysqli_query($con, "SELECT * FROM `user_books` WHERE `bid` = " . $book_id))
{
	$my_book = mysqli_fetch_array($result);
}
else
	echo "False";


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('d-Infinity');
$pdf->SetTitle($my_book["title"]);
$pdf->SetSubject('A multiplatform gaming supplement.');
$pdf->SetKeywords('d-Infinity');

// set default header data
$pdf->SetHeaderData('dilogo.png', PDF_HEADER_LOGO_WIDTH, 'Test Book', 'Content courtesy of d-Infinity.net');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
$html = "";
$articles = json_decode($my_book["content"]);

foreach($articles as $article)
{
	if($result = mysqli_query($con, "SELECT * FROM `node` WHERE `nid` = " . $article))
	{
		while($row = mysqli_fetch_array($result))
		{
			$html = $html . $row["title"];
		}
		if($body = mysqli_query($con, "SELECT * FROM `field_data_body` WHERE `entity_id` = " . $article))
		{
			while($bodyrow = mysqli_fetch_array($body))
			{
				$html = $html . $bodyrow["body_value"];
			}
		}
	}
}

mysqli_close($con);

$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output($my_book["title"], 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
