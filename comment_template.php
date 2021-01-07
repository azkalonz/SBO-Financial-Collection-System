<?php
require 'config/config.php';
require 'api/func.php';
$showing = 4;
$total = 0;
if(isset($_POST["showing"])){
  $showing = $_POST["showing"];
}
function countReplies($id) {
  global $con;
  $query = $con->prepare("SELECT count(id) FROM replies WHERE comment_id = {$id}");
  $query->execute();
  return $query->fetch()[0];}
function grabComments($id){
  global $con;
  global $showing;
  $query = $con->prepare("SELECT * FROM comments WHERE event_id = {$id} AND stat = 1 ORDER BY id DESC LIMIT {$showing}");
  $query->execute();
  return $query->fetchAll(PDO::FETCH_ASSOC);
}
function getSize($id){
  global $con;
  $query = $con->prepare("SELECT count(id) FROM comments WHERE event_id = {$id} AND stat = 1 ORDER BY id DESC");
  $query->execute();
  return $query->fetch()[0];}

if(isset($_POST["grab"])){
  ?>
  <?php if (isset($_SESSION['account'])): ?>
    <div style="text-align:right;">
      <a href="/account/?tab=comment-settings">
        Pending comments
        <?php echo countMyPendingComments($_POST['cid'],$_SESSION['account']['user-id']) ?>
      </a>
    </div>
  <?php endif; ?>
  <?php
  foreach (grabComments($_POST['cid']) as $key => $value){
    $total++;
    $start_date = new DateTime($value['time_interval']);
    $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));
    $h = $since_start->h;
    $m = $since_start->i;
    $s = $since_start->s;
    $d = $since_start->days;
    ?>
    <div class="flex flex-wrap col-100 j__">
      <div class="col-100 flex justify-space-between">
        <h4 style="color: #06ace3;"><?php echo $value['name'] ?></h4>
        <!-- <?php if ($value['student_id']===$_SESSION['account']['student_id']): ?>
          <div class="j_" style="position: relative;">
            <a href="#" style="font-size: 1.5em;" onclick="event.preventDefault();$(this).next().toggle()">
              <i class="fal fa-ellipsis-h"></i>
            </a>
            <div style="display:none;position: absolute;right: 0;box-shadow: 5px 7px 10px rgba(0,0,0,0.1);top:26px;background:#fff;border: .5px solid #bebebe;padding: 10px 20px;border-radius: 3px;">
              <ul style="list-style: none;margin: 0;padding: 0;">
                <li>
                  <a href="#" style="white-space: pre;"><i class="fal fa-trash-alt"></i>Delete comment</a>
                </li>
                <li>
                  <a href="#" style="white-space: pre;"><i class="fal fa-pencil"></i>Edit comment</a>
                </li>
              </ul>
            </div>
          </div>
        <?php endif; ?> -->
      </div>
      <div class="col-100">
        <?php echo $value['comment'] ?>
        <span class="comment-date">
          <a style="cursor: pointer;color:" class="c-link" cidb="<?php echo $value['id'] ?>">
          <?php if(countReplies($value["id"])>0){echo countReplies($value["id"]); ?>
          <?php echo countReplies($value["id"])>1?"comments":"comment"; ?></a> <?php } ?>
          <a href="#" class="c-link" cidb="<?php echo $value['id'] ?>" style="color: #808080;">Reply</a> &#8226;
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
    </div>
      <div class="main-reply-container" style="padding: 0;border-radius: 8px; position:relative;">
        <div class="col-100 main-reply" reply-msg-id="<?php echo $value['id'] ?>" style="display:none;">
        </div>
        <div class="col-100 compose-comment" reply-id="<?php echo $value['id'] ?>" style="display: none;">
          <form onsubmit="return false;">
            <table>
              <tr>
                <td>
                  <div class="c-cont">
                    <textarea name="name" placeholder="Write a reply..." class="comment-msg" rows="1" cols="80" reply-compose-id="<?php echo $value['id'] ?>"></textarea>

                    <div class="flex align-center justify-space-between" style="padding:10px;">
                      <div>
                        <div id="<?php echo "r-{$value['id']}" ?>" style="display: none;">
                          <img src="/dashboard/img/spinner.svg" alt="Posting comment..." style="width:40px!important;"/>
                        </div>
                      </div>
                      <div>
                        <button type="button" class="rep-btn" name="button" comment-trigger-id="<?php echo $value['id'] ?>">Reply</button>
                      </div>

                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>

    <?php
    }
  }
  if(getSize($_POST["cid"])>4 && $total<getSize($_POST["cid"])){
    ?>
    <div style="background: none;">
      <a id="showing_<?php echo $_POST['cid'] ?>" class="show-more" href="<?php echo $showing+4 ?>" showing="<?php echo $showing ?>">Load more comments</a>
    </div>
  <?php }
   if(getSize($_POST["cid"])>4 && $total>4){
    ?>
    <div style="background: none;">
      <a class="show-more" href="<?php echo 4 ?>">Hide comments</a>
    </div>
    <?php
    }

if(isset($_POST['comment']) && isset($_SESSION['account'])){
  $event = $_POST['cid'];
  $msg = str_replace("'","\"",$_POST['message']);
  if(isset($_SESSION['account']['student_id'])){
  $id = $_SESSION['account']['student_id'];}else{
  $id = $_SESSION['account']['officer_id'];}
  $name = $_SESSION['account']['full_name'];
  $time = date('Y-m-d H:i:s');
  $date = date('F d, Y');
  $stat = $_SESSION['account']['access']>=30?1:2;
  $sql = sprintf("INSERT INTO comments(student_id, name, event_id, comment, _date, time_interval, stat)
  VALUES (%d, '%s', %d, '%s', '%s', '%s', %d)", $id, $name, $event, $msg, $date, $time, $stat);
  $query = $con->prepare($sql);
  $query->execute();
  echo $sql;
}
if(!isset($_SESSION['account']) && isset($_POST['comment'])) {
  echo '<span class="error sm">Session expired <a href="/account/login.php">login</a> to continue.</span>';
}
 ?>
<script type="text/javascript">
  $(".c-link[cidb]").off();
  <?php if(isset($_SESSION['account'])): ?>
    $(".c-link[cidb]").on("click",function() {
      event.preventDefault();
      id = $(this).attr("cidb");
      updateReplies(id,$("div[reply-msg-id="+id+"]"),true);
      $("div[reply-msg-id="+id+"]").toggle();
      $("div[reply-id="+id+"]").toggle();
      if($("div[reply-id="+id+"]").is(":visible")){
        localStorage["open-"+id] = true;
      } else {
        localStorage["open-"+id] = false;
      }
      $(".rep-btn").click(()=>{
        if($("textarea[reply-compose-id="+id+"]").val().replace(/\n/g, "<br>").length < 200){
        $(".comment-msg").val("");
        }
      })
    })
    <?php else: ?>
    $(".c-link[cidb]").on("click",function() { window.location='/account/login.php';});
    <?php endif; ?>
    $('.c-options').each(function(){
      $(this).click(function(){
        event.preventDefault();
        $(this).next().toggle();
      })
    });
    open = (e)=>{
      e.next.toggle();
    }
</script>
