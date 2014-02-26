<?php
//include_once("../public/php/head.php");
header("Content-Type: text/html; charset=UTF-8");
include_once("../public/php/utility.php");

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";
$sumAllTotal = 0.00;

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Shanghai');
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../public/PHPExcel_1.7.9/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
->setLastModifiedBy("Maarten Balliauw")
->setTitle("Office 2007 XLSX Test Document")
->setSubject("Office 2007 XLSX Test Document")
->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
->setKeywords("office 2007 openxml php")
->setCategory("Test result file");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Hello')
->setCellValue('B2', 'world!')
->setCellValue('C1', 'Hello')
->setCellValue('D2', 'world!');



// Set document properties
$objPHPExcel->getProperties()->setCreator("正大财务集中支付系统")
->setLastModifiedBy("正大财务集中支付系统")
->setTitle("付款汇总表导出")
->setSubject("付款汇总表导出")
->setDescription("付款汇总表导出到Excel")
->setKeywords("付款汇总表导出到Excel")
->setCategory("付款汇总表导出到Excel");


//表头
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', '部门/项目')
->setCellValue('B1', '费用申请人')
->setCellValue('C1', '付款事由')
->setCellValue('D1', '金额')
->setCellValue('E1', '收款人')
->setCellValue('F1', '银行')
->setCellValue('G1', '账号')
->setCellValue('H1', '备注')
->setCellValue('I1', '付款状态')
->setCellValue('J1', '操作人')
->setCellValue('K1', '操作时间');

//金额靠右
$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//收款人居中
$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


$query = "SELECT * FROM zdcw_payment_master WHERE `NUM`='$NUM'";
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$mst_org = splitName($ROW['ORG']);
	$mst_billnum = $ROW['BILLNUM'];
}



//明细
$query_page = "SELECT * FROM zdcw_payment_detail WHERE `NUM`='$NUM' ORDER BY (ITEMNO+0)";
$cursor_page = exequery($connection,$query_page);
$i = 2;
while($row_page = mysqli_fetch_array($cursor_page)){
	//金额格式化
	$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
	//收款人居中
	$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//银行名称换行
	//$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setWrapText(true);
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$i, splitName($row_page['ORG']))
	->setCellValue('B'.$i, splitName($row_page['APPLICANT']))
	->setCellValue('C'.$i, $row_page['PAYMENT'])
	->setCellValue('D'.$i, $row_page['TOTALAMT'])
	->setCellValue('E'.$i, splitName($row_page['PAYEE']))
	->setCellValue('F'.$i, $row_page['BANK'])
	//->setCellValue('G'.$i, $row_page['ACCOUNT'])
	->setCellValueExplicit('G'.$i, $row_page['ACCOUNT'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValue('H'.$i, $row_page['NOTE'])
	->setCellValue('I'.$i, $row_page['PAYSTAT'])
	->setCellValue('J'.$i, ($row_page['PAYER'] ? splitName($row_page['PAYER']) : ''))
	->setCellValue('K'.$i, ($row_page['PAYTIME'] == '0000-00-00 00:00:00' ? '' : $row_page['PAYTIME']));	
	
	$sumAllTotal = $sumAllTotal + $row_page['TOTALAMT'];
	$i ++;
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$i, '总计：')
->setCellValue('D'.$i, number_format($sumAllTotal, 2, '.', ''));
$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');


// Rename worksheet
$mst_title = '付款汇总表'.$mst_billnum.'-'.$mst_org;
$objPHPExcel->getActiveSheet()->setTitle($mst_title);



// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.iconv("UTF-8", "GBK", $mst_title).'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

?>