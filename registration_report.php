<?php
  require_once './PhpExcel/Classes/PHPExcel.php';
  include('config/config.php');
  include('api/func.php');
  $excel = new PHPExcel();
  $event_id = $_GET['event'];
  $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
  $event = $con->prepare("SELECT * FROM psits_collections WHERE event = {$event_id} ORDER BY _date ASC");
  $event->execute();
  $event = $event->fetchAll(PDO::FETCH_ASSOC);

  $years = $con->prepare("SELECT distinct(_date) from psits_collections WHERE event = {$event_id}");
  $years->execute();
  $years = $years->fetchAll(PDO::FETCH_ASSOC);
  $collections = [];
  foreach ($years as $key => $value) {
    if(!isset($collections[$value['_date']])){
      $collections[$value['_date']] = [];
    }
    foreach ($event as $key2 => $value2) {
      if ($event[$key2]['_date']==$value['_date']) {
        array_push($collections[$value['_date']],$event[$key2]);
      }
    }
  }
  krsort($collections);
  $text_left = array(
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
          )
  );
  $text_right = array(
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
          )
  );
  $text_center = array(
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
          )
  );
  $red_bg = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F2DCDB')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
  );
  $black_bg = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '393939')
        ),
        'font' => array(
          'color'=>array('rgb' => 'FFFFFF')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        )
    );
  $all_border = array(
    'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
      ),
      'alignment' => array(
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );


  // foreach(range('A','F') as $columnID)
  // {
  //     $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  // }

  // HEADER START
  $excel->getActiveSheet()
  ->setCellValue('A1',$department['university']);

  $excel->getActiveSheet()
  ->setCellValue('A2',$department['college']);

  $excel->getActiveSheet()
  ->setCellValue('A3',$labels['text-logo'].' COLLECTION REPORT');

  $excel->getActiveSheet()
  ->setCellValue('A5',getEventName($event_id).' Collection Summary');
  $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);

  $excel->getActiveSheet()->getStyle("A1:E5")->getFont()->setSize(14);
  $excel->getActiveSheet()->getStyle("A5")->getFont()->setSize(18);

  $excel->getActiveSheet()
  ->setCellValue('A6','Student ID');
  $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
  $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

  $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
  $excel->getActiveSheet()
  ->setCellValue('B6','Name');
  $excel->getActiveSheet()
  ->setCellValue('C6','Course');
  $excel->getActiveSheet()
  ->setCellValue('D6','Encoded by');
  $excel->getActiveSheet()
  ->setCellValue('E6','Amount');

  $excel->getActiveSheet()->getStyle('A6:E6')->getFont()->setBold(true);
  $excel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($red_bg);

  $excel->getActiveSheet()
  ->mergeCells('A1:E1');
  $excel->getActiveSheet()
  ->mergeCells('A2:E2');
  $excel->getActiveSheet()
  ->mergeCells('A3:E3');
  $excel->getActiveSheet()
  ->mergeCells('A4:E4');
  $excel->getActiveSheet()
  ->mergeCells('A5:E5');
  $excel->getActiveSheet()->getStyle('A6')->applyFromArray($text_center);

  $excel->getActiveSheet()
  ->setTitle(getEventName($event_id).' - Collection');

  $excel->getActiveSheet()->getStyle('A1:E6')->applyFromArray($text_center);
  // END OF HEADER

  $excel->getActiveSheet()->getStyle("A6:E6")->applyFromArray($text_center);
  $current_row = 7;
  $total = 0;
  foreach ($collections as $key => $value) {
    $excel->getActiveSheet()->setCellValue("A$current_row",$key);
    $excel->getActiveSheet()->mergeCells("A$current_row:E$current_row");
    $excel->getActiveSheet()->getStyle('A'.$current_row)->applyFromArray($black_bg);
    $excel->getActiveSheet()->getStyle("A$current_row")->getFont()->setSize(20);
    $current_row++;
    foreach ($collections[$key] as $key2 => $value2) {
      $total += (float)$value2['payment'];
      $excel->getActiveSheet()->setCellValue("A$current_row",$value2['student_id']);
      $excel->getActiveSheet()->setCellValue("B$current_row",$value2['student_name']);
      $excel->getActiveSheet()->setCellValue("C$current_row","{$value2['course']} - {$value2['_year']}");
      $excel->getActiveSheet()->setCellValue("D$current_row",$value2['encoded_by']);
      $excel->getActiveSheet()->setCellValue("E$current_row",$value2['payment']);
      $excel->getActiveSheet()->getStyle("A$current_row:E$current_row")->applyFromArray($text_center);
      $current_row++;
    }
  }
  $excel->getActiveSheet()->setCellValue("A$current_row","TOTAL");
  $excel->getActiveSheet()->getStyle("A$current_row")->getFont()->setBold(true);
  $excel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($text_right);
  $excel->getActiveSheet()->mergeCells("A$current_row:D$current_row");
  $excel->getActiveSheet()->setCellValue("E$current_row",$total);
  $excel->getActiveSheet()->getStyle("E{$current_row}")->getNumberFormat()->setFormatCode('0.00');




  $excel->getActiveSheet()->getStyle("A1:E$current_row")->applyFromArray($all_border);
  // END OF FOOTER


  header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="'.getEventName($event_id).' collection report'.'.xlsx"');
  header('Cache-Control: max-age=0');


  $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $objWriter->save('php://output');
 ?>
