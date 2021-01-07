<?php include("config/config.php") ?>
<?php
function getReplies($id) {
  global $con;
  $query = $con->prepare("SELECT * FROM replies WHERE comment_id = {$id} ORDER BY id DESC");
  $query->execute();
  return $query->fetchAll(PDO::FETCH_ASSOC);}

  if(isset($_POST["action"])){
    if($_POST["action"]=="insert" && isset($_SESSION['account'])){
      $name = $_SESSION['account']['full_name'];
      $msg = $_POST['message'];
      $time = date('Y-m-d H:i:s');
      $cid = $_POST['commentID'];
      $sql = sprintf("INSERT INTO replies SET comment_id = %d, sender_name = '%s', message = '%s', time_interval = '%s'",$cid,$name,$msg,$time);
      $query= $con->prepare($sql);
      $query->execute();
    }
    if(!isset($_SESSION['account'])) {
      echo '<span class="error sm">Session expired <a href="/account/login.php">login</a> to continue.</span>';
    }
    if(isset($_POST["update"])){
      if (sizeof(getReplies($_POST['commentID']))) {
        echo "<div style='padding-top: 10px;'></div>";
      }
      foreach (getReplies($_POST["commentID"]) as $key => $value2): ?>
      <?php
        $start_date = new DateTime($value2['time_interval']);
        $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));
        $h = $since_start->h;
        $m = $since_start->i;
        $s = $since_start->s;
        $d = $since_start->days;
       ?>
      <div class="reply-item col-100">
        <b style="color: #06acdf;"><?php echo $value2['sender_name'] ?></b>
        <p><?php echo $value2['message'] ?></p>
        <span class="comment-date">
          <a href="#" class="c-link" style="color: #808080;" onclick="event.preventDefault();
          $('textarea[reply-compose-id='+<?php echo $_POST["commentID"] ?>+']').focus()">Reply</a> &#8226;
          <?php
          if($h==0 && $m==0 && $s<60 && $d==0){
            echo "Just now";
          } else if($h==0 && $m>0 && $d==0){
            echo $m."m";
          } else if($h>0 && $h<24 && $d==0){
            echo $h>1?$h."hrs":$h."hr";
          } else if($d>=1){
            echo $d."d";
          } ?>
        </span>
      </div>
      <?php endforeach;
    }
  }
 ?>
<script type="text/javascript">
  $(`#r-<?php echo $_POST["commentID"]?>`).hide();
</script>
