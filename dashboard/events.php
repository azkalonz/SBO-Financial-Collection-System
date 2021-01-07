<?php
  require("../config/config.php");
  require("../api/func.php");
  secure(['admin-only'=>true]);
?>
    <div id="student_list" style="background: #fff;border-radius: 5px;" class="wrap"></div>
<script type="text/javascript">
$("#student_list").load("student_list.php", {start:0});
</script>
