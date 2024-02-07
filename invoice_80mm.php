<?php
// call the FPDF library 
require('fpdf/fpdf.php');

include_once 'connectdb.php';


$id = $_GET["id"];

$select = $pdo->prepare("select * from tbl_invoice where invoice_id=$id");
$select->execute();

$row = $select->fetch(PDO::FETCH_OBJ);



// <!-- A4 width : 219mm -->
// <!-- default margin : 10mm each side -->
// <!-- writable horizontal : 219-(10*2)=199mm -->

// <!-- create pdf object -->
// Specify the orientation(porttrait, landscape), unit of measurement, size(x, y)
$pdf = new FPDF('P', 'mm', array(80, 200));



// <!-- string Orientation (P or L) - portrait or Landscape -->
// <!-- string unit (pt, mm, cm an in) - measure unit -->
// <!-- mixed fromat (A3, A4, A5, Letter and Legal) - format of Pages -->

// <!-- add new page -->
$pdf->AddPage();

// set font to arial, bold, 16pt
$pdf->setFont('Arial', 'B', 16);

// cell(width, height, text, border, end line, [align])
$pdf->Cell(60, 10, 'My Company Inc.', 1, 1, 'C');
$pdf->Cell(60, 2, '', 0, 1, '');

$pdf->setFont('Arial', 'B', 9);
$pdf->Cell(60, 5, 'Progress way, New york - USA', 0, 1, 'C');

$pdf->setFont('Arial', 'B', 9);
$pdf->Cell(30, 5, 'PHONE NUMBER : ', 0, 0, 'C');

$pdf->setFont('Arial', '', 8);
$pdf->Cell(30, 5, '343-2323-2322', 0, 1, '');

$pdf->setFont('Arial', 'B', 9);
$pdf->Cell(30, 5, 'EMAIL ADDRESS : ', 0, 0, 'C');

$pdf->setFont('Arial', '', 8);
$pdf->Cell(30, 5, 'segun@mycompany.com', 0, 1, '');

$pdf->setFont('Arial', 'B', 9);
$pdf->Cell(30, 5, 'Website : ', 0, 0, 'C');

$pdf->setFont('Arial', '', 8);
$pdf->Cell(30, 5, 'www.mycomp.com', 0, 1, '');

// Line(x1, y1, x2, y2)
$pdf->Line(5, 45, 75, 45);

$pdf->Ln(); //line break

// $pdf->Cell(60, 5, '', 0, 1, '');

$pdf->setFont('Arial', 'BI', 9);
$pdf->Cell(20, 5, 'Bill To : ', 0, 0, 'L');

$pdf->setFont('Arial', 'I', 8);
$pdf->Cell(30, 5, $row->customer_name, 0, 1, 'L');

$pdf->setFont('Arial', 'BI', 9);
$pdf->Cell(20, 5, 'Invoice No : ', 0, 0, 'L');

$pdf->setFont('Arial', 'I', 8);
$pdf->Cell(30, 5, $row->invoice_id, 0, 1, 'L');

$pdf->setFont('Arial', 'BI', 9);
$pdf->Cell(20, 5, 'Date : ', 0, 0, 'L');

$pdf->setFont('Arial', 'I', 8);
$pdf->Cell(30, 5, $row->order_date, 0, 1, 'L');


$pdf->Cell(60, 5, '', 0, 1, '');


$pdf->setFont('Arial', 'B', 8); 
$pdf->Cell(24, 5, 'PRODUCT', 1, 0, 'L');
$pdf->Cell(13, 5, 'PRICE', 1, 0, 'C');
$pdf->Cell(12, 5, 'QTY', 1, 0, 'C');
$pdf->Cell(12, 5, 'TOTAL', 1, 1, 'C');


$select_item = $pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select_item->execute();

while($row_item = $select_item->fetch(PDO::FETCH_OBJ)){

    $pdf->setFont('Arial', '', 8); 
    $pdf->Cell(24, 7, $row_item->product_name, 1, 0, 'C');
    $pdf->Cell(13, 7, $row_item->price, 1, 0, 'C');
    $pdf->Cell(12, 7, $row_item->quantity, 1, 0, 'C');
    $pdf->Cell(12, 7, $row_item->price * $row_item->quantity, 1, 1, 'C');
}


$pdf->setFont('Arial', '', 10); 
$pdf->Cell(15, 8, '', 0, 0, 'L');
$pdf->Cell(22, 6, 'Subtotal', 1, 0, 'L');
$pdf->Cell(24, 6, '$ '.$row->subtotal, 1, 1, 'C');

$pdf->setFont('Arial', '', 10); 
$pdf->Cell(15, 8, '', 0, 0, 'L');
$pdf->Cell(22, 6, 'Tax(5%)', 1, 0, 'L');
$pdf->Cell(24, 6, '$ '.$row->tax, 1, 1, 'C');

$pdf->setFont('Arial', '', 10); 
$pdf->Cell(15, 8, '', 0, 0, 'L');
$pdf->Cell(22, 6, 'Discount', 1, 0, 'L');
$pdf->Cell(24, 6, '$ '.$row->discount, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 10); 
$pdf->Cell(15, 8, '', 0, 0, 'L');
$pdf->Cell(22, 6, 'GrandTotal', 1, 0, 'L');
$pdf->Cell(24, 6, '$ '.$row->total, 1, 1, 'C');

$pdf->setFont('Arial', '', 10); 
$pdf->Cell(15, 8, '', 0, 0, 'L');
$pdf->Cell(22, 6, 'Paid', 1, 0, 'L');
$pdf->Cell(24, 6, '$ '.$row->paid, 1, 1, 'C');

$pdf->setFont('Arial', '', 10); 
$pdf->Cell(15, 8, '', 0, 0, 'L');
$pdf->Cell(22, 6, 'Due', 1, 0, 'L');
$pdf->Cell(24, 6, '$ '.$row->due, 1, 1, 'C');


$pdf->Cell(60, 5, '', 0, 1, '');

$pdf->setFont('Arial', 'B', 8);
$pdf->Cell(40, 5, 'Important Note : ', 0, 1, '');

$pdf->setFont('Arial', '', 8);
$pdf->Cell(40, 5, 'No item can be returned or refunded if you don\'t', 0, 1, '');
$pdf->Cell(40, 5, 'have the invoice slip with you.', 0, 1, '');


$pdf->Cell(60, 5, '', 0, 1, '');

$pdf->Cell(33, 5, '', 0, 0, '');

$pdf->setFont('Arial', 'B', 7);
$pdf->Cell(17, 5, 'Created at : ', 0, 0, '');

$pdf->setFont('Arial', '', 8);
$pdf->Cell(10, 5, $row->order_time, 0, 1, 'R');


$pdf->Output();
?>