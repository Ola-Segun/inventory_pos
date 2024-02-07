 
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
$pdf = new FPDF('P', 'mm', 'A4');



// <!-- string Orientation (P or L) - portrait or Landscape -->
// <!-- string unit (pt, mm, cm an in) - measure unit -->
// <!-- mixed fromat (A3, A4, A5, Letter and Legal) - format of Pages -->

// <!-- add new page -->
$pdf->AddPage();


// $pdf->SetFillColor(123, 255, 234);






$pdf->setFont('Arial', 'B', 12);  // inv
$pdf->Cell(112, 10, 'INVOICE', 0, 1, 'C');

$pdf->setFont('Arial', '', 8);  // com
$pdf->Cell(80, 5, 'Address : Progress way, New york', 0, 0, '');

$pdf->setFont('Arial', '', 10);  // inv
$pdf->Cell(112, 5, 'Invoice Number :'.$row->invoice_id, 0, 1, 'C');

$pdf->setFont('Arial', '', 8);  // com
$pdf->Cell(80, 5, 'Phone Number : 343-2323-2322', 0, 0, '');

$pdf->setFont('Arial', '', 10); // inv
$pdf->Cell(112, 5, 'Order Date : '.$row->order_date, 0, 1, 'C');

$pdf->setFont('Arial', '', 8); // com
$pdf->Cell(80, 5, 'Email Address : segun@mycompany.com', 0, 0, '');

$pdf->setFont('Arial', '', 10); // inv
$pdf->Cell(112, 5, 'Order Time : '.$row->order_time, 0, 1, 'C');

$pdf->setFont('Arial', '', 8); // com
$pdf->Cell(80, 5, 'Website : www.mycomp.com', 0, 0, '');



// Line(x1, y1, x2, y2)
$pdf->Line(5, 45, 205, 45);
$pdf->Line(5, 46, 205, 46);

$pdf->Ln(); //line break


$pdf->setFont('Arial', 'BI', 12); 
$pdf->Cell(20, 20, 'Bill to : ', 0, 0, '');

$pdf->setFont('Arial', 'BI', 14); 
$pdf->Cell(50, 20, $row->customer_name, 0, 1, '');


$pdf->setFont('Arial', 'B', 12); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, 'PRODUCT', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'QTY', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'PRICE', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'TOTAL', 1, 1, 'C', true);


$select_item = $pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select_item->execute();

while($row_item = $select_item->fetch(PDO::FETCH_OBJ)){

    $pdf->setFont('Arial', 'B', 11); 
    $pdf->Cell(100, 8, $row_item->product_name, 1, 0, 'C');
    $pdf->Cell(20, 8, $row_item->quantity, 1, 0, 'C');
    $pdf->Cell(30, 8, $row_item->price, 1, 0, 'C');
    $pdf->Cell(40, 8, $row_item->price * $row_item->quantity, 1, 1, 'C');
}



$pdf->setFont('Arial', 'B', 11); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'Subtotal', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->subtotal, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 11); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'Tax', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->tax, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 11); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'Discount', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->discount, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 13); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'GrandTotal', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->total, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 11); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'Paid', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->paid, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 11); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'Due', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->due, 1, 1, 'C');

$pdf->setFont('Arial', 'B', 9); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, 'Payment Type', 1, 0, 'C', true);
$pdf->Cell(40, 8, $row->payment_type, 1, 1, 'C');


$pdf->setFont('Arial', 'B', 9); 
$pdf->Cell(100, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, '', 0, 0, 'C');
$pdf->Cell(30, 8, '', 0, 0, 'C');
$pdf->Cell(40, 8, '', 0, 1, 'C');

$pdf->setFont('Arial', '', 9); 
$pdf->SetFillColor(208, 208, 208);
$pdf->Cell(40, 8, 'Important Notice : ', 0, 0, 'C', true);
$pdf->Cell(150, 8, 'No item can be returned or refunded if you don\'t have the invoice with you', 0, 0, 'R');


// <!-- output the result -->
$pdf->Output();


?>