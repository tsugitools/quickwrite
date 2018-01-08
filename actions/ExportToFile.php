<?php
require_once "../../config.php";
require_once "../util/PHPExcel.php";
require_once "../dao/QW_DAO.php";
require_once "../util/Utils.php";

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if ( $USER->instructor ) {

    $SetID = $_SESSION["SetID"];
   
    $questions = $QW_DAO->getQuestions($SetID);


    $Total = count($questions);

    $exportFile = new PHPExcel();

    
   $exportFile->setActiveSheetIndex(0)->setCellValue('A1', 'Student Name');
	$exportFile->setActiveSheetIndex(0)->setCellValue('B1', 'Question');
	$exportFile->setActiveSheetIndex(0)->setCellValue('C1', 'Response');
	$exportFile->setActiveSheetIndex(0)->setCellValue('D1', 'Date');
	
	
	$exportFile->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$exportFile->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
	$exportFile->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
	$exportFile->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
	$exportFile->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$exportFile->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$exportFile->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$exportFile->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	//$exportFile->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
	
$StudentList = $QW_DAO->Report($SetID);	
   

        $columnIterator = $exportFile->getActiveSheet()->getColumnIterator();
        $columnIterator->next();


      $rowCounter = 1;
  foreach ( $StudentList as $row ) {
 		 //  $rowCounter++;	  
		 //  $exportFile->getActiveSheet()->setCellValue('A'.$rowCounter, $row["FirstName"].' '.$row["LastName"]);

			$UserID = 	$row["UserID"];
			$questions = $QW_DAO->getQuestions($SetID);	
		   
		   foreach ( $questions as $row1 ) {
			   			$rowCounter++;
						$QID = $row1["QID"];
			   			$A="";	
							

						$Data = $QW_DAO->Review($QID, $UserID);	
						foreach ( $Data as $row2 ) {
								$A= $row2["Answer"];
								$Date1 = $row2["Modified"];
						}

			   
		   				$exportFile->getActiveSheet()->setCellValue('A'.$rowCounter, $row["FirstName"].' '.$row["LastName"]);
						$exportFile->getActiveSheet()->setCellValue('B'.$rowCounter, 'Question '.$row1["QNum"]);
						$exportFile->getActiveSheet()->setCellValue('C'.$rowCounter, $A);
			   			$exportFile->getActiveSheet()->setCellValue('D'.$rowCounter, $Date1);

			   			
			   
						$columnIterator->next();
			
    }
            
	  
	  $columnIterator->next();
	  

        
    
}
	$exportFile->getActiveSheet()->setTitle('Quick Write');

	
	
	
foreach($exportFile->getActiveSheet()->getColumnDimension() as $col) {
    $col->setAutoSize(true);
}
$exportFile->getActiveSheet()->calculateColumnWidths();
	
	
	
	
	
	
	
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Quick Write.xls');
        header('Cache-Control: max-age=0');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($exportFile, 'Excel5');
        $objWriter->save('php://output');
}

