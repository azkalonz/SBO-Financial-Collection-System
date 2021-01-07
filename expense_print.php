<?php
  require_once './PhpExcel/Classes/PHPExcel.php';
  include('config/config.php');
  include('api/func.php');
  $excel = new PHPExcel();
  $event_id = $_GET['event'];

  $event = $con->prepare("SELECT * FROM psits_liquidation WHERE event_id = {$event_id} ORDER BY _date ASC");
  $event->execute();
  $event = $event->fetchAll(PDO::FETCH_ASSOC);

  $text_left = array(
          'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
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
            'color' => array('rgb' => '808080')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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
  ->setCellValue('A3',$labels['text-logo'].' FINANCIAL STATEMENT');

  $excel->getActiveSheet()
  ->setCellValue('A5',getEventName($event_id).' Expenses Summary');
  $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);

  $excel->getActiveSheet()->getStyle("A1:F5")->getFont()->setSize(14);
  $excel->getActiveSheet()->getStyle("A5")->getFont()->setSize(18);

  $excel->getActiveSheet()
  ->setCellValue('A6','Date');
  $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
  $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
  $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

  $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
  $excel->getActiveSheet()
  ->setCellValue('B6','Particulars');
  $excel->getActiveSheet()
  ->setCellValue('C6','Total Amount');
  $excel->getActiveSheet()
  ->setCellValue('D6','Cash Received');
  $excel->getActiveSheet()
  ->setCellValue('E6','Money Left');
  $excel->getActiveSheet()
  ->setCellValue('F6','Receipt Reference');

  $excel->getActiveSheet()->getStyle('A6:F6')->getFont()->setBold(true);
  $excel->getActiveSheet()->getStyle('A6:F6')->applyFromArray($red_bg);

  $excel->getActiveSheet()
  ->mergeCells('A1:F1');
  $excel->getActiveSheet()
  ->mergeCells('A2:F2');
  $excel->getActiveSheet()
  ->mergeCells('A3:F3');
  $excel->getActiveSheet()
  ->mergeCells('A4:F4');
  $excel->getActiveSheet()
  ->mergeCells('A5:F5');

  $excel->getActiveSheet()
  ->setTitle(getEventName($event_id).' - Expenses');
  // END OF HEADER


  // ORGANIZING DATA
  $current_row = 7;
  $total_money_left = 0;
  $total_received = 0;
  $total_amount = 0;

  // Array for liquidations per year
  $year_sort = array();

  // Initialize years $year_sort[2019],$year_sort[2018]...
  foreach ($event as $key => $value) {
    $year_sort[explode(', ',$value['_date'])[1]] = [];
  }
  // Filter liquidations by year
  foreach ($year_sort as $year => $date) {
    foreach ($event as $key => $value) {
      if($year == explode(', ',$value['_date'])[1]){
        array_push($year_sort[$year], $value);
      }
    }
  }
  // END OF ORGANIZING DATA

  // DISPLAYING DATA
  $excel->getActiveSheet()->freezePane('G7');
  // Loop through indexes of $year_sort
  foreach ($year_sort as $year => $sortval) {
    // Empty row
    $excel->getActiveSheet()->setCellValue('A'.$current_row,'');

    // Merge empty row & add black background
    $excel->getActiveSheet()->mergeCells("A{$current_row}:F{$current_row}");
    $excel->getActiveSheet()->getStyle('A'.$current_row)->applyFromArray($black_bg);

    // Next row
    $current_row++;
    // Set cell value to the year of data
    $excel->getActiveSheet()->setCellValue('A'.$current_row,$year);
    $excel->getActiveSheet()->mergeCells("A{$current_row}:F{$current_row}");
    $excel->getActiveSheet()->getStyle("A{$current_row}")->getFont()->setSize(18);

    // Add red background
    $excel->getActiveSheet()->getStyle('A'.$current_row)->applyFromArray($red_bg);
    $current_row++;

    // Loop through data of the current year $year_sort[2019] => [data]...
    foreach ($year_sort[$year] as $key => $value) {
      // Make list of particulars
      $items = explode(', ',$value['details']);
      $receipts = explode(', ',$value['receipts']);
      $amounts = explode(', ',$value['amount']);

      // Set cell value of Date (F d, Y) format, received amount and money left
      $excel->getActiveSheet()->setCellValue('A'.$current_row,$value['_date']);
      $excel->getActiveSheet()->setCellValue('D'.$current_row,sprintf('%.2f',getRecevied($value['exp_id'])));
      $excel->getActiveSheet()->setCellValue('E'.$current_row,sprintf('%.2f',getMoneyLeft2($value['exp_id'])));

      // Loop through the particulars
      for ($i=0; $i < sizeof($items); $i++) {
        // $items[i] is the description
        // $receipts[i] is the receipt
        // $amounts[i] is the amount
        $excel->getActiveSheet()->setCellValue('B'.$current_row,$items[$i]);
        $excel->getActiveSheet()->setCellValue('C'.$current_row,sprintf('%.2f',(float)$amounts[$i]));
        $excel->getActiveSheet()->setCellValue('F'.$current_row,$receipts[$i]);
        $excel->getActiveSheet()->getStyle("B{$current_row}:E{$current_row}")->applyFromArray($red_bg);
        $excel->getActiveSheet()->getStyle("B{$current_row}:E{$current_row}")->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle("B{$current_row}:E{$current_row}")->getNumberFormat()->setFormatCode('0.00');
        $current_row++;
      }

      // Merge common data of current year (Date, Received, and Amount row)
      $mergeStart = $current_row-sizeof($items);
      $mergeEnd = $current_row-1;
      $excel->getActiveSheet()
      ->mergeCells("A{$mergeStart}:A{$mergeEnd}");
      $excel->getActiveSheet()
      ->mergeCells("E{$mergeStart}:E{$mergeEnd}");
      $excel->getActiveSheet()
      ->mergeCells("D{$mergeStart}:D{$mergeEnd}");

      // Variables for total amount
      $total_money_left+= getMoneyLeft2($value['exp_id']);
      $total_received+= getRecevied($value['exp_id']);
      $total_amount+= getAmount($value['exp_id']);
    }
  }
  // Extra row
  $excel->getActiveSheet()->mergeCells("A{$current_row}:F{$current_row}");
  $excel->getActiveSheet()->getStyle('A'.$current_row)->applyFromArray($black_bg);
  $current_row++;
  // END OF BODY

  // FOOTER
  $excel->getActiveSheet()
  ->setCellValue("A{$current_row}",'TOTAL');
  $excel->getActiveSheet()
  ->setCellValue("B{$current_row}",'');
  $excel->getActiveSheet()
  ->setCellValue("C{$current_row}",$total_amount);
  $excel->getActiveSheet()
  ->setCellValue("D{$current_row}",$total_received);
  $excel->getActiveSheet()
  ->setCellValue("E{$current_row}",$total_money_left);
  $excel->getActiveSheet()->getStyle("B{$current_row}:E{$current_row}")->getNumberFormat()->setFormatCode('0.00');
  $excel->getActiveSheet()->getStyle("A1:F{$current_row}")->applyFromArray($all_border);
  // END OF FOOTER


  header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="'.getEventName($event_id).' financial statement'.'.xlsx"');
  header('Cache-Control: max-age=0');
  $url = $images['logo'];
  $gdImage = imagecreatefromstring(file_get_contents($url));
  $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
  $objDrawing->setName('Sample image');
  $objDrawing->setDescription('Sample image');
  $objDrawing->setImageResource($gdImage);
  $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
  $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
  $objDrawing->setHeight(120);
  $objDrawing->setCoordinates('A1');
  $objDrawing->setWorksheet($excel->getActiveSheet());

  $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $objWriter->save('php://output');
 ?>
