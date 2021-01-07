<?php
  require '../config/config.php';
  secure(['admin-only'=>true]);
 ?>
<div class="wrap" style="background: #fff;border-radius: 5px;">
  <div id="collection_view" style="position:relative;">
  </div>
</div>
<script type="text/javascript">
  $("#collection_view").load("collection_list.php");
</script>
