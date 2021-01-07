<?php
// for future internal logging...
(function(){
  return;
  $myFile = "logs.txt";
  $fh = fopen($myFile, 'a') or die("can't open file");
  fwrite($fh, "\n\n--------------------------------------\n");
  $time = date('m/d/Y h:i:s A', $_SERVER['REQUEST_TIME']);
  fwrite($fh, "[{$time}] : METHOD: {$_SERVER['REQUEST_METHOD']}, REMOTE_IP: {$_SERVER['REMOTE_ADDR']}");
  fwrite($fh, file_get_contents('php://input'));
  fclose($fh);
})();
?>
  <meta property="og:description"
    content="Philippines Society of Information Technology Students - University of Cebu - LM" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:site_name" content="PSITS - UCLM" />
  <meta property="og:type" content="website" />
  <meta name="keywords" content="PSITS, UCLM, University of Cebu, Philippines Society of Information Technology Students">
  <meta name="author" content="College of Computer Studies - UCLM">
  <meta property="og:image" content="https://i.imgur.com/FwCBMtk.jpg" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script
src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
crossorigin="anonymous"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<!-- <script type="text/javascript" src="/js/jquery.sticky.js"></script> -->
